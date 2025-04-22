import React, { useEffect, useState } from 'react'
import { router, Head, Link, usePage } from '@inertiajs/react'
import { isEmpty } from 'lodash'

import AuthenticatedLayout from '@/layouts/default/authenticated-layout'
import {
    Card,
    Button,
    TextInput,
    FormInputNumeric,
    Label,
    SelectModalInput,
} from '@/components/index'
import { HiXMark } from 'react-icons/hi2'

export default function Form(props) {
    const {
        props: { errors },
    } = usePage()
    const { bundle } = props

    const [processing, setProcessing] = useState(false)

    const [name, setName] = useState('')
    const [session_quote, set_session_quote] = useState('')
    const [active_period_days, set_active_period_days] = useState('')
    const [price, set_price] = useState('')
    const [items, set_items] = useState([])

    const addItem = (item) => {
        const exists = items.find((i) => i.subject_id === item.id)
        if (exists) {
            return
        }

        set_items(
            items.concat({
                subject_id: item.id,
                subject: item,
            })
        )
    }

    const removeItem = (index) => {
        set_items(items.filter((_, i) => i !== index))
    }

    const handleSubmit = () => {
        const payload = {
            name,
            session_quote,
            active_period_days,
            price,
            items,
        }
        if (isEmpty(bundle) === false) {
            router.put(route('bundles.update', bundle), payload, {
                onStart: () => setProcessing(true),
                onFinish: (e) => {
                    setProcessing(false)
                },
            })
            return
        }
        router.post(route('bundles.store'), payload, {
            onStart: () => setProcessing(true),
            onFinish: (e) => {
                setProcessing(false)
            },
        })
    }

    useEffect(() => {
        if (!isEmpty(bundle)) {
            setName(bundle.name)
            set_session_quote(bundle.session_quote)
            set_active_period_days(bundle.active_period_days)
            set_price(bundle.price)
            set_items(bundle.items)
        }
    }, [bundle])

    return (
        <AuthenticatedLayout
            page={'System'}
            action={'Paket'}
        >
            <Head title="Paket" />

            <div>
                <Card>
                    <div className="flex flex-col gap-2 justify-between">
                        <TextInput
                            name="name"
                            value={name}
                            onChange={(e) => setName(e.target.value)}
                            label="Nama"
                            error={errors.name}
                        />
                        <FormInputNumeric
                            name="price"
                            value={price}
                            onChange={(e) => set_price(e.target.value)}
                            label="Harga"
                            error={errors.price}
                        />
                        <FormInputNumeric
                            name="session_quote"
                            value={session_quote}
                            onChange={(e) => set_session_quote(e.target.value)}
                            label="Jatah Sesi"
                            error={errors.session_quote}
                        />
                        <FormInputNumeric
                            name="active_period_days"
                            value={active_period_days}
                            onChange={(e) =>
                                set_active_period_days(e.target.value)
                            }
                            label="Masa Aktif (Hari)"
                            error={errors.active_period_days}
                        />
                        <div className="w-full flex flex-col gap-2">
                            <Label label="Kelas" />
                            <SelectModalInput
                                placeholder="Pilih Kelas"
                                onChange={addItem}
                                params={{
                                    table: 'subjects',
                                    columns: 'id|name',
                                    orderby: 'updated_at.desc',
                                }}
                            />
                            <table className="table">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th className="w-[50px]" />
                                    </tr>
                                </thead>
                                <tbody>
                                    {items.map((item, index) => (
                                        <tr key={index}>
                                            <td>{item.subject.name}</td>
                                            <td className="text-right">
                                                <div
                                                    onClick={() =>
                                                        removeItem(index)
                                                    }
                                                >
                                                    <HiXMark className="h-5 w-5 text-red-500" />
                                                </div>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
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
                                <Link href={route('bundles.index')}>
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
