import React, { useEffect, useState } from 'react'
import { router, Head, Link } from '@inertiajs/react'
import { usePrevious } from 'react-use'
import {
    HiCreditCard,
    HiEye,
    HiPencil,
    HiPrinter,
    HiTrash,
} from 'react-icons/hi2'
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
} from '@/components/index'
import FormModal from './form-modal'

export default function Index(props) {
    const {
        data: { links, data },
    } = props

    const [search, setSearch] = useState('')
    const preValue = usePrevious(search)

    const confirmModal = useModalState()
    const formModal = useModalState()

    const toggleFormModal = (member = null) => {
        formModal.setData(member)
        formModal.toggle()
    }

    const handleDeleteClick = (member) => {
        confirmModal.setData(member)
        confirmModal.toggle()
    }

    const onDelete = () => {
        if (confirmModal.data !== null) {
            router.delete(route('members.destroy', confirmModal.data.id))
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
            action={'Member'}
        >
            <Head title="Member" />

            <div>
                <Card>
                    <div className="flex justify-between mb-4">
                        <HasPermission p="create-member">
                            <Button
                                size="sm"
                                onClick={() => toggleFormModal()}
                                type="primary"
                            >
                                Add
                            </Button>
                        </HasPermission>
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
                                    <th>Kategori</th>
                                    <th>No.Telp</th>
                                    <th>Gender</th>
                                    <th>Keterangan</th>
                                    <th className="w-[100px]" />
                                </tr>
                            </thead>
                            <tbody>
                                {data.map((member, index) => (
                                    <tr key={member.id}>
                                        <td>{member.code}</td>
                                        <td>{member.name}</td>
                                        <td>{member.category?.name}</td>
                                        <td>{member.phone}</td>
                                        <td>{member.gender}</td>
                                        <td>{member.description}</td>
                                        <td className="text-end">
                                            <div className="flex justify-end gap-2">
                                                <Link
                                                    href={route(
                                                        'members.show',
                                                        member.id
                                                    )}
                                                >
                                                    <Button>
                                                        <HiEye />
                                                    </Button>
                                                </Link>
                                                <Dropdown>
                                                    <Dropdown.Item>
                                                        <a
                                                            className="flex space-x-1 items-center"
                                                            href={route(
                                                                'members.print',
                                                                member.id
                                                            )}
                                                            target="_blank"
                                                        >
                                                            <HiPrinter />
                                                            <div>
                                                                Cetak Kartu
                                                            </div>
                                                        </a>
                                                    </Dropdown.Item>
                                                    <HasPermission p="update-member">
                                                        <Dropdown.Item
                                                            onClick={() =>
                                                                toggleFormModal(
                                                                    member
                                                                )
                                                            }
                                                        >
                                                            <div className="flex space-x-1 items-center">
                                                                <HiPencil />
                                                                <div>Edit</div>
                                                            </div>
                                                        </Dropdown.Item>
                                                    </HasPermission>
                                                    <HasPermission p="delete-member">
                                                        <Dropdown.Item
                                                            onClick={() =>
                                                                handleDeleteClick(
                                                                    member
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
                onConfirm={onDelete}
                modalState={confirmModal}
            />
            <FormModal modalState={formModal} />
        </AuthenticatedLayout>
    )
}
