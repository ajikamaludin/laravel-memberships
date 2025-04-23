import React, { useEffect } from 'react'
import { useForm } from '@inertiajs/react'
import { isEmpty } from 'lodash'

import {
    Modal,
    Button,
    TextInput,
    SelectModalInput,
    TextareaInput,
    FormInputDate,
    FormInputNumeric,
    SelectOptionArray,
    SelectOptionObject,
} from '@/components/index'

export default function FormModal(props) {
    const { modalState } = props
    const formState = {
        description: '',
        account_id: '',
        type: 'in',
        amount: '',
        transaction_date: new Date(),
        account: null,
    }

    const { data, setData, post, put, processing, errors, clearErrors } =
        useForm(formState)

    const handleOnChange = (event) => {
        setData(
            event.target.name,
            event.target.type === 'checkbox'
                ? event.target.checked
                    ? 1
                    : 0
                : event.target.value
        )
    }

    const handleReset = () => {
        modalState.setData(null)
        setData(formState)
        clearErrors()
    }

    const handleClose = () => {
        handleReset()
        modalState.toggle()
    }

    const handleSubmit = () => {
        const journal = modalState.data
        if (journal !== null) {
            put(route('journals.update', journal), {
                onSuccess: () => handleClose(),
            })
            return
        }
        post(route('journals.store'), {
            onSuccess: () => handleClose(),
        })
    }

    useEffect(() => {
        const journal = modalState.data
        if (isEmpty(journal) === false) {
            setData(journal)
            return
        }
    }, [modalState])

    return (
        <Modal
            isOpen={modalState.isOpen}
            onClose={handleClose}
            title={'Keuangan'}
        >
            <div className="form-control space-y-2.5">
                <SelectModalInput
                    label="Akun"
                    value={data.account}
                    onChange={(item) => {
                        setData({
                            ...data,
                            account: item,
                            account_id: item.id,
                        })
                    }}
                    error={errors.account_id}
                    params={{
                        table: 'accounts',
                        columns: 'id|name',
                        headers: 'Nama',
                        display_name: 'name',
                        orderby: 'updated_at.desc',
                    }}
                />
                <SelectOptionObject
                    name="type"
                    value={data.type}
                    onChange={(e) => setData('type', e.target.value)}
                    label="Tipe"
                    error={errors.type}
                    options={{ in: 'Pemasukan', out: 'Pengeluaran' }}
                />
                <FormInputDate
                    name="transaction_date"
                    value={data.transaction_date}
                    onChange={(date) => setData('transaction_date', date)}
                    label="Tanggal"
                    error={errors.transaction_date}
                />
                <FormInputNumeric
                    name="amount"
                    value={data.amount}
                    onChange={(e) => setData('amount', e.target.value)}
                    label="Jumlah"
                    error={errors.amount}
                />
                <TextareaInput
                    name="description"
                    value={data.description}
                    onChange={handleOnChange}
                    label="Keterangan"
                    error={errors.description}
                />

                <div className="flex items-center space-x-2 mt-4">
                    <Button
                        onClick={handleSubmit}
                        processing={processing}
                        type="primary"
                    >
                        Save
                    </Button>
                    <Button
                        onClick={handleClose}
                        type="secondary"
                    >
                        Cancel
                    </Button>
                </div>
            </div>
        </Modal>
    )
}
