import React, { useEffect, useState } from 'react'
import { router } from '@inertiajs/react'
import { usePrevious } from 'react-use'
import { Head, Link } from '@inertiajs/react'
import { HiPencil, HiTrash } from 'react-icons/hi2'
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
import { formatIDR } from '@/utils'

export default function Index(props) {
    const {
        data: { links, data },
    } = props

    const [search, setSearch] = useState('')
    const preValue = usePrevious(search)

    const confirmModal = useModalState()

    const handleDeleteClick = (bundle) => {
        confirmModal.setData(bundle)
        confirmModal.toggle()
    }

    const onDelete = () => {
        if (confirmModal.data !== null) {
            router.delete(route('bundles.destroy', confirmModal.data.id))
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
            action={'Paket'}
        >
            <Head title="Paket" />

            <div>
                <Card>
                    <div className="flex justify-between">
                        <HasPermission p="create-bundle">
                            <Link href={route('bundles.create')}>
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
                                    <th>Nama</th>
                                    <th>Harga</th>
                                    <th>Jatah Sesi</th>
                                    <th>Masa Aktif (Hari)</th>
                                    <th />
                                </tr>
                            </thead>
                            <tbody>
                                {data.map((bundle, index) => (
                                    <tr key={bundle.id}>
                                        <td>{bundle.name}</td>
                                        <td>{formatIDR(bundle.price)}</td>
                                        <td>
                                            {formatIDR(bundle.session_quote)}
                                        </td>
                                        <td>
                                            {formatIDR(
                                                bundle.active_period_days
                                            )}
                                        </td>
                                        <td className="text-right">
                                            <Dropdown>
                                                <HasPermission p="update-bundle">
                                                    <Dropdown.Item
                                                        onClick={() =>
                                                            router.visit(
                                                                route(
                                                                    'bundles.edit',
                                                                    bundle
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
                                                <HasPermission p="delete-bundle">
                                                    <Dropdown.Item
                                                        onClick={() =>
                                                            handleDeleteClick(
                                                                bundle
                                                            )
                                                        }
                                                    >
                                                        <div className="flex space-x-1 items-center">
                                                            <HiTrash />
                                                            <div>Delete</div>
                                                        </div>
                                                    </Dropdown.Item>
                                                </HasPermission>
                                            </Dropdown>
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
