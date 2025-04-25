import React, { useEffect, useState } from 'react'
import { router, Head, Link, usePage } from '@inertiajs/react'
import { isEmpty } from 'lodash'
import { HiPlus } from 'react-icons/hi2'

import AuthenticatedLayout from '@/layouts/default/authenticated-layout'
import {
    Card,
    Button,
    TextInput,
    SelectModalInput,
    FormInputNumeric,
    FormInputDate,
    Label,
} from '@/components/index'
import FormMember from '../member/form-modal'
import SelectMember from '../member/select-modal'
import { useModalState } from '@/hooks'
import { dateToString, formatIDR } from '@/utils'

export default function Form(props) {
    const {
        props: { errors },
    } = usePage()
    const { transaction } = props

    const [processing, setProcessing] = useState(false)

    const selectMember = useModalState()
    const formMember = useModalState()

    const [member, set_member] = useState(null)
    const [account, set_account] = useState(null)
    const [transaction_date, set_transaction_date] = useState(
        dateToString(new Date())
    )
    const [discount, set_discount] = useState('')

    const [bundle, set_bundle] = useState(null)
    const [join_fee, set_join_fee] = useState('')

    const amount =
        Number(bundle?.price ?? 0) +
        Number(join_fee ?? 0) -
        Number(discount ?? 0)

    const handleSetMember = (member) => {
        if (member === null) {
            set_member(null)
            set_join_fee(0)
            return
        }
        set_member(member)
        set_join_fee(member.category.join_fee)
    }

    const handleSubmit = () => {
        let items = []
        if (bundle !== null) {
            items.push({
                name: bundle.name,
                price: bundle.price,
                bundle_id: bundle.id,
            })
        }

        if (Number(join_fee ?? 0) !== 0) {
            items.push({
                name: 'Join Fee',
                price: join_fee,
                bundle_id: null,
            })
        }

        const payload = {
            transaction_date,
            account_id: account?.id,
            member_id: member?.id,
            amount,
            discount,
            items,
        }
        if (isEmpty(transaction) === false) {
            router.put(route('transactions.update', transaction), payload, {
                onStart: () => setProcessing(true),
                onFinish: (e) => {
                    setProcessing(false)
                },
            })
            return
        }
        router.post(route('transactions.store'), payload, {
            onStart: () => setProcessing(true),
            onFinish: (e) => {
                setProcessing(false)
            },
        })
    }

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
                    <div className="flex flex-col gap-2 justify-between">
                        <div className="grid grid-cols-2 gap-2">
                            <FormInputDate
                                label="Tanggal"
                                value={transaction_date}
                                onChange={(date) => set_transaction_date(date)}
                                error={errors.transaction_date}
                            />
                            <SelectModalInput
                                label="Akun Penerimaan Pembayaran"
                                value={account}
                                onChange={(item) => set_account(item)}
                                onRemove={() => set_account(null)}
                                error={errors.account_id}
                                params={{
                                    table: 'accounts',
                                    columns: 'id|name',
                                    headers: 'Nama',
                                    display_name: 'name',
                                    orderby: 'updated_at.desc',
                                }}
                            />
                        </div>
                        <fieldset className="fieldset">
                            <Label label="Member" />
                            <div className="w-full flex flex-row gap-2 items-center">
                                <div className="flex-1">
                                    <TextInput
                                        readOnly={true}
                                        value={`${
                                            member
                                                ? `${member.code} | ${member.name} | ${member.phone} `
                                                : ''
                                        }`}
                                        onClick={() => selectMember.toggle()}
                                        error={errors.member_id}
                                    />
                                </div>
                                <div className="">
                                    <Button onClick={formMember.toggle}>
                                        <HiPlus />
                                    </Button>
                                </div>
                            </div>
                        </fieldset>
                        <div className="grid grid-cols-2 gap-2">
                            <SelectModalInput
                                label="Paket"
                                value={bundle}
                                onChange={(item) => set_bundle(item)}
                                onRemove={() => set_bundle(null)}
                                error={errors.bundle_id}
                                params={{
                                    table: 'bundles',
                                    columns: 'id|name|price',
                                    headers: 'Nama|Harga',
                                    display_name: 'name|price',
                                    orderby: 'updated_at.desc',
                                }}
                            />
                            <TextInput
                                label="Harga Paket"
                                value={formatIDR(bundle?.price ?? 0)}
                                onChange={() => {}}
                                readOnly={true}
                            />
                            <FormInputNumeric
                                label="Join Fee"
                                value={join_fee}
                                onChange={(e) => set_join_fee(e.target.value)}
                            />
                            <FormInputNumeric
                                label="Diskon"
                                value={discount}
                                onChange={(e) => set_discount(e.target.value)}
                            />
                        </div>
                        <div className="text-xl font-bold flex flex-row gap-2">
                            <div>TOTAL : </div>
                            <div>{formatIDR(amount)}</div>
                        </div>
                        <div className="flex items-center">
                            <div className="flex space-x-2">
                                <Button
                                    onClick={handleSubmit}
                                    processing={processing}
                                    type="primary"
                                >
                                    Save
                                </Button>
                                <Link href={route('transactions.index')}>
                                    <Button type="secondary">Back</Button>
                                </Link>
                            </div>
                        </div>
                    </div>
                </Card>
            </div>
            <FormMember
                modalState={formMember}
                onCreated={handleSetMember}
            />
            <SelectMember
                modalState={selectMember}
                onItemClick={handleSetMember}
            />
        </AuthenticatedLayout>
    )
}
