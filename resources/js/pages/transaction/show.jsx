import React, { useEffect, useState } from 'react'
import { router, Head, Link, usePage } from '@inertiajs/react'
import { isEmpty } from 'lodash'
import { HiPencil, HiPlus, HiPrinter } from 'react-icons/hi2'

import AuthenticatedLayout from '@/layouts/default/authenticated-layout'
import {
    Card,
    Button,
    TextInput,
    SelectModalInput,
    FormInputNumeric,
    FormInputDate,
} from '@/components/index'
import FormMember from '../member/form-modal'
import { useModalState } from '@/hooks'
import { formatDate, formatIDR } from '@/utils'
import HasPermission from '@/components/common/has-permission'

export default function Show(props) {
    const { transaction } = props

    const [member, set_member] = useState(null)
    const [account, set_account] = useState(null)
    const [transaction_date, set_transaction_date] = useState(new Date())
    const [discount, set_discount] = useState('')

    const [bundle, set_bundle] = useState(null)
    const [join_fee, set_join_fee] = useState('')

    const amount =
        Number(bundle?.price ?? 0) +
        Number(join_fee ?? 0) -
        Number(discount ?? 0)

    useEffect(() => {
        if (!isEmpty(transaction)) {
            set_member(transaction.member)
            set_account(transaction.account)
            set_transaction_date(transaction.transaction_date)
            set_discount(transaction.discount)
            set_bundle(
                transaction.items.filter((i) => i.bundle_id !== null)[0]
                    ?.bundle ?? null
            )
            // not use the join fee name , current condition the join fee is no bundle id
            set_join_fee(
                transaction.items.find((i) => i.bundle_id === null)?.price ?? 0
            )
        }
    }, [transaction])

    return (
        <AuthenticatedLayout
            page={'System'}
            action={'Transaksi'}
        >
            <Head title="Transaksi" />

            <div>
                <Card>
                    <div className="flex flex-row gap-2">
                        <HasPermission p="update-transaction">
                            <Link
                                href={route(
                                    'transactions.edit',
                                    transaction.id
                                )}
                            >
                                <Button>
                                    <HiPencil />
                                    <span>Edit</span>
                                </Button>
                            </Link>
                        </HasPermission>
                        <a
                            href={route('transactions.print', transaction.id)}
                            target="_blank"
                        >
                            <Button>
                                <HiPrinter />
                                <span>Cetak Invoice</span>
                            </Button>
                        </a>
                    </div>
                    <div className="flex flex-col gap-2 justify-between">
                        <TextInput
                            label="Tanggal"
                            value={formatDate(transaction_date)}
                            readOnly={true}
                        />
                        <TextInput
                            label="Akun Penerimaan Pembayaran"
                            value={account?.name}
                            readOnly={true}
                        />
                        <TextInput
                            label="Member"
                            value={member?.name}
                            readOnly={true}
                        />
                        <TextInput
                            label="Paket"
                            value={bundle?.name}
                            readOnly={true}
                        />
                        <TextInput
                            label="Harga Paket"
                            value={formatIDR(bundle?.price ?? 0)}
                            readOnly={true}
                        />
                        <TextInput
                            label="Join Fee"
                            value={formatIDR(join_fee)}
                            readOnly={true}
                        />
                        <TextInput
                            label="Diskon"
                            value={formatIDR(discount)}
                            readOnly={true}
                        />
                        <div className="text-xl font-bold flex flex-row gap-2">
                            <div>TOTAL : </div>
                            <div>{formatIDR(amount)}</div>
                        </div>
                        <div className="flex items-center">
                            <div className="flex space-x-2">
                                <Link href={route('transactions.index')}>
                                    <Button type="secondary">Back</Button>
                                </Link>
                            </div>
                        </div>
                    </div>
                </Card>
            </div>
        </AuthenticatedLayout>
    )
}
