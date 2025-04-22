import React, { useEffect } from 'react'
import { useForm } from '@inertiajs/react'
import { isEmpty } from 'lodash'

import { Modal, Button, TextInput, FormInputNumeric } from '@/components/index'

export default function FormModal(props) {
    const { modalState } = props
    const formState = {
        name: '',
        balance_start: 0,
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
        const account = modalState.data
        if (account !== null) {
            put(route('accounts.update', account), {
                onSuccess: () => handleClose(),
            })
            return
        }
        post(route('accounts.store'), {
            onSuccess: () => handleClose(),
        })
    }

    useEffect(() => {
        const account = modalState.data
        if (isEmpty(account) === false) {
            setData(account)
            return
        }
    }, [modalState])

    return (
        <Modal
            isOpen={modalState.isOpen}
            onClose={handleClose}
            title={'Akun'}
        >
            <div className="form-control space-y-2.5">
                <TextInput
                    name="name"
                    value={data.name}
                    onChange={handleOnChange}
                    label="Nama"
                    error={errors.name}
                />
                <FormInputNumeric
                    name="balance_start"
                    value={data.balance_start}
                    onChange={handleOnChange}
                    label="Saldo Awal"
                    error={errors.balance_start}
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
