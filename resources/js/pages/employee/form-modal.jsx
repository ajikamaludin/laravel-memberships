import React, { useEffect } from 'react'
import { useForm } from '@inertiajs/react'
import { isEmpty } from 'lodash'

import { Modal, Button, TextInput, FormInputNumeric } from '@/components/index'

export default function FormModal(props) {
    const { modalState } = props
    const formState = {
        name: '',
        position: '',
        basic_salary_per_session: '',
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
        const employee = modalState.data
        if (employee !== null) {
            put(route('employees.update', employee), {
                onSuccess: () => handleClose(),
            })
            return
        }
        post(route('employees.store'), {
            onSuccess: () => handleClose(),
        })
    }

    useEffect(() => {
        const employee = modalState.data
        if (isEmpty(employee) === false) {
            setData(employee)
            return
        }
    }, [modalState])

    return (
        <Modal
            isOpen={modalState.isOpen}
            onClose={handleClose}
            title={'Karyawan'}
        >
            <div className="form-control space-y-2.5">
                <TextInput
                    name="name"
                    value={data.name}
                    onChange={handleOnChange}
                    label="Nama"
                    error={errors.name}
                />
                <TextInput
                    name="position"
                    value={data.position}
                    onChange={handleOnChange}
                    label="Posisi"
                    error={errors.position}
                />
                <FormInputNumeric
                    name="basic_salary_per_session"
                    value={data.basic_salary_per_session}
                    onChange={handleOnChange}
                    label="Gaji Pokok"
                    error={errors.basic_salary_per_session}
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
