import React, { useEffect } from 'react'
import { useForm } from '@inertiajs/react'
import { isEmpty } from 'lodash'

import {
    Modal,
    Button,
    TextInput,
    FormInputNumeric,
    TextareaInput,
} from '@/components/index'

export default function FormModal(props) {
    const { modalState } = props
    const formState = {
        name: '',
        description: '',
        employee_fee_per_person: '',
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
        const subject = modalState.data
        if (subject !== null) {
            put(route('subjects.update', subject), {
                onSuccess: () => handleClose(),
            })
            return
        }
        post(route('subjects.store'), {
            onSuccess: () => handleClose(),
        })
    }

    useEffect(() => {
        const subject = modalState.data
        if (isEmpty(subject) === false) {
            setData(subject)
            return
        }
    }, [modalState])

    return (
        <Modal
            isOpen={modalState.isOpen}
            onClose={handleClose}
            title={'Subject'}
        >
            <div className="form-control space-y-2.5">
                <TextInput
                    name="name"
                    value={data.name}
                    onChange={handleOnChange}
                    label="Name"
                    error={errors.name}
                />
                <FormInputNumeric
                    name="employee_fee_per_person"
                    value={data.employee_fee_per_person}
                    onChange={handleOnChange}
                    label="Fee"
                    error={errors.employee_fee_per_person}
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
