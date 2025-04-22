import React, { useEffect, useState } from 'react'
import { router } from '@inertiajs/react'
import { usePrevious } from 'react-use'
import { Head, Link } from '@inertiajs/react'
import { HiPencil, HiPrinter, HiTrash } from 'react-icons/hi2'
import { useModalState } from '@/hooks'

import AuthenticatedLayout from '@/layouts/default/authenticated-layout'
import HasPermission from '@/components/common/has-permission'
import {
    Pagination,
    ModalConfirm,
    SearchInput,
    Dropdown,
    Button,
    Card,
} from '@/components/index'
import { formatDate, formatIDR } from '@/utils'

export default function Index(props) {
    const {
        data: { links, data },
    } = props

    const [search, setSearch] = useState('')
    const preValue = usePrevious(search)

    const confirmModal = useModalState()

    const handleDeleteClick = (transaction) => {
        confirmModal.setData(transaction)
        confirmModal.toggle()
    }

    const onDelete = () => {
        if (confirmModal.data !== null) {
            router.delete(route('transactions.destroy', confirmModal.data.id))
        }
    }

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
            action={'Transaksi'}
        >
            <Head title="Transaksi" />

            <div>
                <Card>
                    <div className="flex justify-between">
                        <HasPermission p="create-transaction">
                            <Link href={route('transactions.create')}>
                                <Button
                                    size="sm"
                                    type="primary"
                                >
                                    Add
                                </Button>
                            </Link>
                        </HasPermission>

                        <div className="flex items-center">
                            <SearchInput
                                onChange={(e) => setSearch(e.target.value)}
                                value={search}
                            />
                        </div>
                    </div>
                    <div className="overflow-x-auto">
                        <table className="table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Member</th>
                                    <th>Total</th>
                                    <th>Diskon</th>
                                    <th>Penerimaan</th>
                                    <th />
                                </tr>
                            </thead>
                            <tbody>
                                {data.map((transaction, index) => (
                                    <tr key={transaction.id}>
                                        <td>
                                            {formatDate(
                                                transaction.transaction_date
                                            )}
                                        </td>
                                        <td>{transaction.member.name}</td>
                                        <td>{formatIDR(transaction.amount)}</td>
                                        <td>
                                            {formatIDR(transaction.discount)}
                                        </td>
                                        <td>{transaction.account.name}</td>
                                        <td className="text-right">
                                            <div className="flex justify-end gap-2">
                                                <a
                                                    href={route(
                                                        'transactions.print',
                                                        transaction.id
                                                    )}
                                                    target="_blank"
                                                >
                                                    <Button>
                                                        <HiPrinter />
                                                    </Button>
                                                </a>
                                                <Dropdown>
                                                    <HasPermission p="update-transaction">
                                                        <Dropdown.Item
                                                            onClick={() =>
                                                                router.visit(
                                                                    route(
                                                                        'transactions.edit',
                                                                        transaction
                                                                    )
                                                                )
                                                            }
                                                        >
                                                            <div className="flex space-x-1 items-center">
                                                                <HiPencil />
                                                                <div>Edit</div>
                                                            </div>
                                                        </Dropdown.Item>
                                                    </HasPermission>
                                                    <HasPermission p="delete-transaction">
                                                        <Dropdown.Item
                                                            onClick={() =>
                                                                handleDeleteClick(
                                                                    transaction
                                                                )
                                                            }
                                                        >
                                                            <div className="flex space-x-1 items-center">
                                                                <HiTrash />
                                                                <div>
                                                                    Delete
                                                                </div>
                                                            </div>
                                                        </Dropdown.Item>
                                                    </HasPermission>
                                                </Dropdown>
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
            <ModalConfirm
                modalState={confirmModal}
                onConfirm={onDelete}
            />
        </AuthenticatedLayout>
    )
}
