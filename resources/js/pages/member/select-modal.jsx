import { Modal, PaginationApi, SearchInput, Spinner } from '@/components/index'
import { useDebounce, useSelectApiPagination } from '@/hooks'
import { usePage } from '@inertiajs/react'
import { useEffect, useState } from 'react'

export default function SelectModal(props) {
    const {
        props: { auth },
    } = usePage()
    const { modalState, onItemClick, subjectId } = props

    const [search, setSearch] = useState('')
    const q = useDebounce(search, 750)

    const [data, fetch, loading] = useSelectApiPagination(
        auth,
        {
            q,
            subject_id: subjectId,
        },
        'api.members.index'
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
            title={`Member`}
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
                    <table className="table mt-3">
                        <thead>
                            <tr>
                                <th>Member ID</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>No.Telp</th>
                                <th>Gender</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            {data.data?.map((item) => (
                                <tr
                                    key={item.id}
                                    className={`hover:bg-base-300`}
                                    onClick={() => handleItemClick(item)}
                                >
                                    <td>{item.code}</td>
                                    <td>{item.name}</td>
                                    <td>{item.category?.name}</td>
                                    <td>{item.phone}</td>
                                    <td>{item.gender}</td>
                                    <td>{item.description}</td>
                                </tr>
                            ))}
                        </tbody>
                    </table>

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
