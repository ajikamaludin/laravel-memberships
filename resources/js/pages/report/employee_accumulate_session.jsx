import React, { useEffect, useState } from 'react'
import { router, Head, Link } from '@inertiajs/react'
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
    const { data, _year, years } = props

    return (
        <AuthenticatedLayout
            page={'System'}
            action={'Laporan Akumulasi Total Sesi Kelas Per Coach'}
        >
            <Head title="Laporan Akumulasi Total Sesi Kelas Per Coach" />

            <div>
                <Card>
                    <div className="flex gap-2 mb-4">
                        <Dropdown label={`Year : ${_year}`}>
                            {years.map((i) => (
                                <Dropdown.Item key={`year-${i}`}>
                                    <Link
                                        href={route(route().current(), {
                                            year: i,
                                        })}
                                        className="flex space-x-1 items-center"
                                    >
                                        {i}
                                    </Link>
                                </Dropdown.Item>
                            ))}
                        </Dropdown>
                    </div>
                    <div className="overflow-x-auto">
                        <table className="table mb-4">
                            <thead>
                                <tr>
                                    <th>Periode</th>
                                    <th>Karyawan</th>
                                    <th>Total Sesi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {data.map((item, index) => (
                                    <tr key={item.id}>
                                        <td>{item.month}</td>
                                        <td>{item.employee.name}</td>
                                        <td>
                                            {
                                                item.employee
                                                    .subject_sessions_count
                                            }
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </Card>
            </div>
        </AuthenticatedLayout>
    )
}
