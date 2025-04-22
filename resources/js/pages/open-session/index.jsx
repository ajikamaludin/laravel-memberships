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
import { formatDate } from '@/utils'
import Form from './form'

export default function Index(props) {
    const {
        data: { links, data },
    } = props

    const [search, setSearch] = useState('')
    const preValue = usePrevious(search)

    const confirmModal = useModalState()

    const handleDeleteClick = (openSession) => {
        confirmModal.setData(openSession)
        confirmModal.toggle()
    }

    const onDelete = () => {
        if (confirmModal.data !== null) {
            router.delete(route('open-sessions.destroy', confirmModal.data.id))
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
            action={'OpenSession'}
        >
            <Head title="OpenSession" />

            <div className="flex flex-col gap-2">
                <HasPermission p="create-open-session">
                    <Form />
                </HasPermission>
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
                        <table className="table mb-4">
                            <thead>
                                <tr>
                                    <th>Member ID</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Tanggal</th>
                                    <th />
                                </tr>
                            </thead>
                            <tbody>
                                {data.map((item, index) => (
                                    <tr key={item.id}>
                                        <td>{item.member.code}</td>
                                        <td>{item.member.name}</td>
                                        <td>{item.subject.name}</td>
                                        <td>{formatDate(item.session_date)}</td>
                                        <td className="text-end">
                                            <HasPermission p="delete-open-session">
                                                <Button
                                                    onClick={() =>
                                                        handleDeleteClick(item)
                                                    }
                                                >
                                                    <div className="flex space-x-1 items-center">
                                                        <HiTrash />
                                                        <div>Delete</div>
                                                    </div>
                                                </Button>
                                            </HasPermission>
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
        </AuthenticatedLayout>
    )
}
