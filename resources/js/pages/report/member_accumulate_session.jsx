import React, { useEffect, useState } from 'react'
import { router, Head } from '@inertiajs/react'
import { usePrevious } from 'react-use'
import { HiPencil, HiTrash } from 'react-icons/hi2'
import { useModalState } from '@/hooks'

import HasPermission from '@/components/common/has-permission'
import AuthenticatedLayout from '@/layouts/default/authenticated-layout'
import {
    Pagination,
    ModalConfirm,
    SearchInput,
    Button,
    Dropdown,
    Card,
    FormInputDateRange,
} from '@/components/index'
import { formatDate, formatIDR } from '@/utils'

export default function MemberSession(props) {
    const {
        data: { links, data },
        _start_date,
        _end_date,
    } = props

    const [dates, setDates] = useState({
        start_date: _start_date,
        end_date: _end_date,
    })
    const [search, setSearch] = useState('')
    const preValue = usePrevious([search, dates])

    const params = { q: search, ...dates }
    useEffect(() => {
        if (preValue) {
            router.get(route(route().current()), params, {
                replace: true,
                preserveState: true,
            })
        }
    }, [search, dates])

    return (
        <AuthenticatedLayout
            page={'System'}
            action={'Laporan Member Datang Per Kelas & Sesi'}
        >
            <Head title="Laporan Member Datang Per Kelas & Sesi" />

            <div>
                <Card>
                    <div className="flex gap-2 mb-4">
                        <FormInputDateRange
                            value={dates}
                            onChange={(dates) => setDates(dates)}
                        />
                        <div className="flex items-center">
                            <SearchInput
                                onChange={(e) => setSearch(e.target.value)}
                                value={search}
                            />
                        </div>
                    </div>
                    <div className="overflow-x-auto">
                        <table className="table mb-4">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kelas</th>
                                    <th>Karyawan</th>
                                    <th>Waktu Sesi</th>
                                    <th>Akumulasi Member</th>
                                </tr>
                            </thead>
                            <tbody>
                                {data.map((item, index) => (
                                    <tr key={item.id}>
                                        <td>{formatDate(item.session_date)}</td>
                                        <td>{item.subject.name}</td>
                                        <td>{item.employee.name}</td>
                                        <td>{item.training_time.name}</td>
                                        <td>{item.items_count}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                    <div className="w-full overflow-x-auto flex lg:justify-center">
                        <Pagination
                            links={links}
                            params={params}
                        />
                    </div>
                </Card>
            </div>
        </AuthenticatedLayout>
    )
}
