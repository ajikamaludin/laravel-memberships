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
} from '@/components/index'
import FormModal from './form-modal'
import { formatIDR } from '@/utils'

export default function Index(props) {
    const {
        data: { links, data },
    } = props

    const [search, setSearch] = useState('')
    const preValue = usePrevious(search)

    const confirmModal = useModalState()
    const formModal = useModalState()

    const toggleFormModal = (subject = null) => {
        formModal.setData(subject)
        formModal.toggle()
    }

    const handleDeleteClick = (subject) => {
        confirmModal.setData(subject)
        confirmModal.toggle()
    }

    const onDelete = () => {
        if (confirmModal.data !== null) {
            router.delete(route('subjects.destroy', confirmModal.data.id))
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
            action={'Kelas'}
        >
            <Head title="Kelas" />

            <div>
                <Card>
                    <div className="flex justify-between mb-4">
                        <HasPermission p="create-subject">
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
                        <table className="table mb-4">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Fee</th>
                                    <th>Keterangan</th>
                                    <th />
                                </tr>
                            </thead>
                            <tbody>
                                {data.map((subject, index) => (
                                    <tr key={subject.id}>
                                        <td>{subject.name}</td>
                                        <td>
                                            {formatIDR(
                                                subject.employee_fee_per_person
                                            )}
                                        </td>
                                        <td>{subject.description}</td>
                                        <td className="text-end">
                                            <Dropdown>
                                                <HasPermission p="update-subject">
                                                    <Dropdown.Item
                                                        onClick={() =>
                                                            toggleFormModal(
                                                                subject
                                                            )
                                                        }
                                                    >
                                                        <div className="flex space-x-1 items-center">
                                                            <HiPencil />
                                                            <div>Edit</div>
                                                        </div>
                                                    </Dropdown.Item>
                                                </HasPermission>
                                                <HasPermission p="delete-subject">
                                                    <Dropdown.Item
                                                        onClick={() =>
                                                            handleDeleteClick(
                                                                subject
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
                onConfirm={onDelete}
                modalState={confirmModal}
            />
            <FormModal modalState={formModal} />
        </AuthenticatedLayout>
    )
}
