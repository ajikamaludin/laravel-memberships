import React, { useEffect, useState } from 'react'
import { router, Head } from '@inertiajs/react'
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
    Label,
} from '@/components/index'
import FormModal from './form-modal'
import { formatDate } from '@/utils'

export default function Show(props) {
    const { member } = props

    return (
        <AuthenticatedLayout
            page={'System'}
            action={'Member'}
        >
            <Head title="Member" />

            <div className="flex flex-col gap-2">
                <Card>
                    <div className="flex flex-row gap-2">
                        <a
                            href={route('members.print', member.id)}
                            target="_blank"
                        >
                            <Button>
                                <HiPrinter />
                                Cetak
                            </Button>
                        </a>
                    </div>
                    <div className="flex flex-col lg:flex-row justify-between gap-2">
                        <table className="table">
                            <tbody>
                                <tr>
                                    <td className="w-[150px]">Member ID </td>
                                    <td className="w-[2px]">:</td>
                                    <td>{member.code}</td>
                                </tr>
                                <tr>
                                    <td className="w-[150px]">Kategori </td>
                                    <td className="w-[2px]">:</td>
                                    <td>{member.category.name}</td>
                                </tr>
                                <tr>
                                    <td className="w-[150px]">Nama </td>
                                    <td className="w-[2px]">:</td>
                                    <td>{member.name}</td>
                                </tr>
                                <tr>
                                    <td className="w-[150px]">Gender </td>
                                    <td className="w-[2px]">:</td>
                                    <td>{member.gender}</td>
                                </tr>
                                <tr>
                                    <td className="w-[150px]">Alamat </td>
                                    <td className="w-[2px]">:</td>
                                    <td>{member.address}</td>
                                </tr>
                                <tr>
                                    <td className="w-[150px]">No.Telp </td>
                                    <td className="w-[2px]">:</td>
                                    <td>{member.phone}</td>
                                </tr>
                                <tr>
                                    <td className="w-[150px]">Keterangan </td>
                                    <td className="w-[2px]">:</td>
                                    <td>{member.description}</td>
                                </tr>
                            </tbody>
                        </table>
                        {member.photo !== null && (
                            <div>
                                <img
                                    src={route('file.show', member.photo)}
                                    className="w-[200px]"
                                />
                            </div>
                        )}
                    </div>
                </Card>
                <Card>
                    <Label label={'Membership'} />
                    <table className="table">
                        <thead>
                            <tr>
                                <th>Paket</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Berakhir</th>
                                <th>Jatah Sesi</th>
                                <th>Sesi Terpakai</th>
                            </tr>
                        </thead>
                        <tbody>
                            {member.memberships.map((membership) => (
                                <tr key={membership.id}>
                                    <td>{membership.bundle.name}</td>
                                    <td>
                                        {formatDate(
                                            membership.transaction
                                                .transaction_date
                                        )}
                                    </td>
                                    <td>{formatDate(membership.expired_at)}</td>
                                    <td>{membership.session_quote}</td>
                                    <td>{membership.session_quote_used}</td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </Card>
            </div>
        </AuthenticatedLayout>
    )
}
