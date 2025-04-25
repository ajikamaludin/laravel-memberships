import React, { useEffect } from 'react'
import { useForm } from '@inertiajs/react'
import { isEmpty } from 'lodash'

import { Modal, Button, TextInput, FormInputNumeric } from '@/components/index'

export default function FormModal(props) {
    const { modalState } = props
    const formState = {
        name: '',
        join_fee: '',
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
        const memberCategory = modalState.data
        if (memberCategory !== null) {
            put(route('member-categories.update', memberCategory), {
                onSuccess: () => handleClose(),
            })
            return
        }
        post(route('member-categories.store'), {
            onSuccess: () => handleClose(),
        })
    }

    useEffect(() => {
        const memberCategory = modalState.data
        if (isEmpty(memberCategory) === false) {
            setData(memberCategory)
            return
        }
    }, [modalState])

    return (
        <Modal
            isOpen={modalState.isOpen}
            onClose={handleClose}
            title={'Kategori Member'}
        >
            <div className="form-control space-y-2.5">
                <TextInput
                    name="name"
                    value={data.name}
                    onChange={handleOnChange}
                    label="Nama"
                    error={errors.name}
                />
                {/* <FormInputNumeric
                    name="join_fee"
                    value={data.join_fee}
                    onChange={handleOnChange}
                    label="Join Fee"
                    error={errors.join_fee}
                /> */}

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
