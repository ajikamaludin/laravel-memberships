import React, { useEffect, useState } from 'react'
import { router } from '@inertiajs/react'
import { usePrevious } from 'react-use'
import { Head, Link } from '@inertiajs/react'
import { HiEye, HiPencil, HiPrinter, HiTrash } from 'react-icons/hi2'
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

    const handleDeleteClick = (employeePayment) => {
        confirmModal.setData(employeePayment)
        confirmModal.toggle()
    }

    const onDelete = () => {
        if (confirmModal.data !== null) {
            router.delete(
                route('employee-payments.destroy', confirmModal.data.id)
            )
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
            action={'Gaji'}
        >
            <Head title="Gaji" />

            <div>
                <Card>
                    <div className="flex justify-between">
                        <HasPermission p="create-employee-payment">
                            <Link href={route('employee-payments.create')}>
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
                        <table className="table mt-6">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama</th>
                                    <th className="text-right">Jumlah</th>
                                    <th>Keterangan</th>
                                    <th />
                                </tr>
                            </thead>
                            <tbody>
                                {data.map((item, index) => (
                                    <tr key={item.id}>
                                        <td>{formatDate(item.payment_date)}</td>
                                        <td>{item.employee.name}</td>
                                        <td className="text-right">
                                            {formatIDR(item.amount)}
                                        </td>
                                        <td>{item.description}</td>
                                        <td className="text-right">
                                            <div className="flex justify-end gap-2">
                                                <a
                                                    href={route(
                                                        'employee-payments.print',
                                                        item.id
                                                    )}
                                                    target="_blank"
                                                >
                                                    <Button>
                                                        <HiPrinter />
                                                    </Button>
                                                </a>

                                                <Dropdown>
                                                    <Dropdown.Item
                                                        onClick={() =>
                                                            router.visit(
                                                                route(
                                                                    'employee-payments.show',
                                                                    item
                                                                )
                                                            )
                                                        }
                                                    >
                                                        <div className="flex space-x-1 items-center">
                                                            <HiEye />
                                                            <div>Detail</div>
                                                        </div>
                                                    </Dropdown.Item>
                                                    <HasPermission p="update-employee-payment">
                                                        <Dropdown.Item
                                                            onClick={() =>
                                                                router.visit(
                                                                    route(
                                                                        'employee-payments.edit',
                                                                        item
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
                                                    <HasPermission p="delete-employee-payment">
                                                        <Dropdown.Item
                                                            onClick={() =>
                                                                handleDeleteClick(
                                                                    item
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
