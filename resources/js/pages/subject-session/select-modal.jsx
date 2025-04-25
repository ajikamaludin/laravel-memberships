import { Modal, PaginationApi, SearchInput, Spinner } from '@/components/index'
import { useDebounce, useSelectApiPagination } from '@/hooks'
import { formatDate } from '@/utils'
import { usePage } from '@inertiajs/react'
import { useEffect, useState } from 'react'

export default function SelectModal(props) {
    const {
        props: { auth },
    } = usePage()
    const {
        modalState,
        onItemClick,
        employeeId,
        selectedDate,
        selectedDateEnd,
    } = props

    const [search, setSearch] = useState('')
    const q = useDebounce(search, 750)

    const [data, fetch, loading] = useSelectApiPagination(
        auth,
        {
            q,
            employee_id: employeeId,
            selected_date: selectedDate,
            selected_date_end: selectedDateEnd,
        },
        'api.subject-sessions.index'
    )

    const handleItemClick = (item) => {
        onItemClick(item)
        modalState.toggle()
    }

    useEffect(() => {
        fetch(1, { q })
    }, [q])

    useEffect(() => {
        if (modalState.isOpen === true) {
            fetch(1)
        }
    }, [modalState])

    return (
        <Modal
            isOpen={modalState.isOpen}
            onClose={modalState.toggle}
            size={'xl'}
            title={`Sesi Kelas`}
        >
            <div className="mb-3"></div>
            <SearchInput
                value={search}
                onChange={(e) => setSearch(e.target.value)}
            />
            {loading ? (
                <div className="w-full flex justify-center items-center gap-4 mt-3 h-36">
                    <Spinner />
                    <div>Loading </div>
                </div>
            ) : (
                <>
                    <div className="w-full overflow-x-auto">
                        <table className="table mt-3">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Waktu Sesi</th>
                                    <th>Kelas</th>
                                    <th>Karyawan</th>
                                    <th>Jumlah Member</th>
                                </tr>
                            </thead>
                            <tbody>
                                {data.data?.map((item) => (
                                    <tr
                                        key={item.id}
                                        className={`hover:bg-base-300`}
                                        onClick={() => handleItemClick(item)}
                                    >
                                        <td>{formatDate(item.session_date)}</td>
                                        <td>{item.training_time.name}</td>
                                        <td>{item.subject.name}</td>
                                        <td>{item.employee.name}</td>
                                        <td>{item.items_count}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>

                    <div className="w-full flex justify-center mt-2">
                        <PaginationApi
                            links={data}
                            page={data.current_page}
                            onPageChange={fetch}
                        />
                    </div>
                </>
            )}
        </Modal>
    )
}
