import React, { useEffect } from 'react'
import { useForm } from '@inertiajs/react'
import { isEmpty } from 'lodash'

import { Modal, Button, TextInput, FormInputTime } from '@/components/index'

export default function FormModal(props) {
    const { modalState } = props
    const formState = {
        name: '',
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
        const trainingTime = modalState.data
        if (trainingTime !== null) {
            put(route('training-times.update', trainingTime), {
                onSuccess: () => handleClose(),
            })
            return
        }
        post(route('training-times.store'), {
            onSuccess: () => handleClose(),
        })
    }

    useEffect(() => {
        const trainingTime = modalState.data
        if (isEmpty(trainingTime) === false) {
            setData(trainingTime)
            return
        }
    }, [modalState])

    return (
        <Modal
            isOpen={modalState.isOpen}
            onClose={handleClose}
            title={'Waktu Sesi'}
        >
            <div className="form-control space-y-2.5 min-h-[300px]">
                <FormInputTime
                    name="name"
                    value={data.name}
                    onChange={(data) => setData('name', data)}
                    label="Waktu"
                    error={errors.name}
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
