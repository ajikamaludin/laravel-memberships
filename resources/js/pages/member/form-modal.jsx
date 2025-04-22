import React, { useEffect } from 'react'
import { useForm } from '@inertiajs/react'
import { isEmpty } from 'lodash'

import {
    Modal,
    Button,
    TextInput,
    SelectModalInput,
    SelectOptionArray,
    FormFile,
    TextareaInput,
} from '@/components/index'

export default function FormModal(props) {
    const { modalState, onCreated } = props
    const formState = {
        name: '',
        member_category_id: '',
        gender: '',
        phone: '',
        photo: '',
        description: '',
        category: null,
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
        const member = modalState.data
        if (member !== null) {
            put(route('members.update', member), {
                onSuccess: () => handleClose(),
            })
            return
        }
        post(route('members.store'), {
            onSuccess: ({
                props: {
                    flash: {
                        data: { member },
                    },
                },
            }) => {
                handleClose()
                if ((typeof onCreated === 'function') === false) {
                    return
                }
                onCreated(member)
            },
        })
    }

    useEffect(() => {
        const member = modalState.data
        if (isEmpty(member) === false) {
            setData(member)
            return
        }
    }, [modalState])

    return (
        <Modal
            isOpen={modalState.isOpen}
            onClose={handleClose}
            title={'Member'}
        >
            <div className="form-control space-y-2.5">
                <SelectModalInput
                    label="Kategori"
                    onChange={(item) =>
                        setData({
                            ...data,
                            category: item,
                            member_category_id: item.id,
                        })
                    }
                    value={data.category}
                    error={errors.member_category_id}
                    params={{
                        table: 'member_categories',
                        columns: 'id|name',
                        orderby: 'updated_at.desc',
                    }}
                />
                <TextInput
                    name="name"
                    value={data.name}
                    onChange={handleOnChange}
                    label="Nama"
                    error={errors.name}
                />
                <TextInput
                    name="phone"
                    value={data.phone}
                    onChange={handleOnChange}
                    label="No.Telp"
                    error={errors.phone}
                />
                <SelectOptionArray
                    name="gender"
                    label="Jenis Kelamin"
                    value={data.gender}
                    onChange={handleOnChange}
                    error={errors.gender}
                    options={['Laki-laki', 'Perempuan']}
                />
                <FormFile
                    label="Foto"
                    error={errors.photo}
                    onChange={(file_path) => setData('photo', file_path)}
                    filemimes="image/jpg,image/jpeg,image/png"
                />
                {data.photo && (
                    <div className="w-full">
                        <img
                            src={route('file.show', data.photo)}
                            alt="member"
                            className="w-[200px]"
                        />
                    </div>
                )}
                <TextareaInput
                    name="description"
                    value={data.description}
                    onChange={handleOnChange}
                    label="Deskripsi"
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
