import React, { useEffect, useState } from 'react'
import { Head, Link, router } from '@inertiajs/react'

import AuthenticatedLayout from '@/layouts/default/authenticated-layout'
import { Dropdown, Card, FormInputDateRange } from '@/components/index'
import { formatDate, formatIDR } from '@/utils'
import { usePrevious } from 'react-use'

export default function MemberSession(props) {
    const { data, _start_date, _end_date } = props

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
            action={'Laba/Rugi'}
        >
            <Head title="Laba/Rugi" />

            <div>
                <Card>
                    <div className="flex gap-2 mb-4">
                        <FormInputDateRange
                            value={dates}
                            onChange={(dates) => setDates(dates)}
                        />
                    </div>
                    <div className="overflow-x-auto">
                        <table className="table mb-4">
                            <thead>
                                <tr>
                                    <th>Keterangan</th>
                                    <th>Jumlah</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                {data.map((item, index) => (
                                    <tr key={index}>
                                        <td className={`${item.font}`}>
                                            {item.name}
                                        </td>
                                        <td className={`${item.font}`}>
                                            {formatIDR(item.amount)}
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
