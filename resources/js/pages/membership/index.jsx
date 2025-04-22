import React, { useEffect, useState } from 'react'
import { router, Head, Link } from '@inertiajs/react'
import { usePrevious } from 'react-use'
import { HiEye } from 'react-icons/hi2'

import AuthenticatedLayout from '@/layouts/default/authenticated-layout'
import { Pagination, SearchInput, Button, Card } from '@/components/index'
import { formatDate, formatIDR } from '@/utils'

export default function Index(props) {
    const {
        data: { links, data },
    } = props

    const [search, setSearch] = useState('')
    const preValue = usePrevious(search)

    const params = { q: search }
    useEffect(() => {
        if (preValue) {
            router.get(
                route(route().current()),
                { q: search },
                {
                    replace: true,
                    preserveState: true,
                }
            )
        }
    }, [search])

    return (
        <AuthenticatedLayout
            page={'System'}
            action={'Membership'}
        >
            <Head title="Membership" />

            <div>
                <Card>
                    <div className="flex justify-between mb-4">
                        <div className="flex items-center">
                            <SearchInput
                                onChange={(e) => setSearch(e.target.value)}
                                value={search}
                            />
                        </div>
                    </div>
                    <div className="overflow-x-auto">
                        <table className="table my-4">
                            <thead>
                                <tr>
                                    <th>Member ID</th>
                                    <th>Nama</th>
                                    <th>Paket</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Berakhir</th>
                                    <th>Jatah Sesi</th>
                                    <th>Sesi Terpakai</th>
                                    <th className="w-[100px]" />
                                </tr>
                            </thead>
                            <tbody>
                                {data.map((item, index) => (
                                    <tr key={item.id}>
                                        <td>{item.member.code}</td>
                                        <td>{item.member.name}</td>
                                        <td>{item.bundle.name}</td>
                                        <td>
                                            {formatDate(
                                                item.transaction
                                                    .transaction_date
                                            )}
                                        </td>
                                        <td>{formatDate(item.expired_at)}</td>
                                        <td>{item.session_quote}</td>
                                        <td>{item.session_quote_used}</td>
                                        <td className="text-end">
                                            <div className="flex justify-end gap-2">
                                                <Link
                                                    href={route(
                                                        'members.show',
                                                        item.member.id
                                                    )}
                                                >
                                                    <Button>
                                                        <HiEye />
                                                    </Button>
                                                </Link>
                                            </div>
                                        </td>
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
