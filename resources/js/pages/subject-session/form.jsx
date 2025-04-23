import React, { useEffect, useState } from 'react'
import { router, Head, Link, usePage } from '@inertiajs/react'
import { isEmpty } from 'lodash'

import AuthenticatedLayout from '@/layouts/default/authenticated-layout'
import {
    Card,
    Button,
    TextInput,
    SelectModalInput,
    FormInputDate,
    Label,
} from '@/components/index'
import SelectModalMember from '../member/select-modal'
import { useModalState } from '@/hooks'
import { HiXMark } from 'react-icons/hi2'
import { toast } from 'sonner'
import { dateToString, showToast } from '@/utils'

export default function Form(props) {
    const {
        props: { errors },
    } = usePage()
    const { subjectSession } = props

    const modalMember = useModalState()
    const [processing, setProcessing] = useState(false)

    const [subject, set_subject] = useState(null)
    const [employee, set_employee] = useState('')
    const [training_time, set_training_time] = useState('')
    const [session_date, set_session_date] = useState(dateToString(new Date()))
    const [items, set_items] = useState([])

    const handleToggleModalMember = () => {
        if (subject === null) {
            showToast('Pilih Kelas terlebih dahulu', 'error')
            return
        }
        modalMember.toggle()
    }

    const handleSetSubject = (subject) => {
        set_subject(subject)
        set_items([])
    }

    const addItem = (item) => {
        const exists = items.find((i) => i.member_id === item.id)
        if (exists) {
            return
        }

        set_items(
            items.concat({
                member_id: item.id,
                member: item,
                membership: item.memberships[0] ?? {},
            })
        )
    }

    const removeItem = (index) => {
        set_items(items.filter((_, i) => i !== index))
    }

    const handleSubmit = () => {
        const payload = {
            subject_id: subject?.id,
            employee_id: employee?.id,
            training_time_id: training_time?.id,
            session_date,
            items,
        }
        if (isEmpty(subjectSession) === false) {
            router.put(
                route('subject-sessions.update', subjectSession),
                payload,
                {
                    onStart: () => setProcessing(true),
                    onFinish: (e) => {
                        setProcessing(false)
                    },
                }
            )
            return
        }
        router.post(route('subject-sessions.store'), payload, {
            onStart: () => setProcessing(true),
            onFinish: (e) => {
                setProcessing(false)
            },
        })
    }

    useEffect(() => {
        if (!isEmpty(subjectSession)) {
            set_employee(subjectSession.employee)
            set_session_date(subjectSession.session_date)
            set_subject(subjectSession.subject)
            set_training_time(subjectSession.training_time)
            set_items(subjectSession.items)
        }
    }, [subjectSession])

    return (
        <AuthenticatedLayout
            page={'System'}
            action={'Sesi Kelas'}
        >
            <Head title="Sesi Kelas" />

            <div>
                <Card>
                    <div className="flex flex-col gap-2 justify-between">
                        <div className="grid grid-cols-2 gap-2">
                            <FormInputDate
                                value={session_date}
                                onChange={(e) => set_session_date(e)}
                                label="Tanggal Sesi"
                                error={errors.session_date}
                            />
                            <SelectModalInput
                                label="Waktu Sesi"
                                onChange={(item) => set_training_time(item)}
                                value={training_time}
                                error={errors.training_time_id}
                                params={{
                                    table: 'training_times',
                                    columns: 'id|name',
                                    orderby: 'updated_at.desc',
                                }}
                            />
                            <SelectModalInput
                                label="Kelas"
                                onChange={(item) => handleSetSubject(item)}
                                value={subject}
                                error={errors.subject_id}
                                params={{
                                    table: 'subjects',
                                    columns: 'id|name',
                                    orderby: 'updated_at.desc',
                                }}
                            />
                            <SelectModalInput
                                label="Karyawan"
                                onChange={(item) => set_employee(item)}
                                value={employee}
                                error={errors.employee_id}
                                params={{
                                    table: 'employees',
                                    columns: 'id|name|position',
                                    headers: 'Nama|Posisi',
                                    orderby: 'updated_at.desc',
                                }}
                            />
                        </div>
                        <div className="flex flex-col gap-2">
                            <Label label="Member" />
                            <TextInput
                                placeholder="pilih member"
                                onClick={handleToggleModalMember}
                            />
                            <div className="w-full">
                                <table className="table">
                                    <thead>
                                        <tr>
                                            <th>Member ID</th>
                                            <th>Nama</th>
                                            <th>Kategori</th>
                                            <th>No.Telp</th>
                                            <th>Gender</th>
                                            <th>Jatah Sesi</th>
                                            <th>Sesi Terpakai</th>
                                            <th className="w-[50px]"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {items.map((item, index) => (
                                            <tr key={index}>
                                                <td>{item.member.code}</td>
                                                <td>{item.member.name}</td>
                                                <td>
                                                    {item.member.category.name}
                                                </td>
                                                <td>{item.phone}</td>
                                                <td>{item.gender}</td>
                                                <td>
                                                    {
                                                        item?.membership
                                                            ?.session_quote
                                                    }
                                                </td>
                                                <td>
                                                    {
                                                        item?.membership
                                                            ?.session_quote_used
                                                    }
                                                </td>
                                                <td>
                                                    <Button
                                                        size="xs"
                                                        onClick={() =>
                                                            removeItem(index)
                                                        }
                                                    >
                                                        <HiXMark className="text-red-500" />
                                                    </Button>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
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
                                <Link href={route('subject-sessions.index')}>
                                    <Button type="secondary">Back</Button>
                                </Link>
                            </div>
                        </div>
                    </div>
                </Card>
            </div>
            <SelectModalMember
                modalState={modalMember}
                subjectId={subject?.id}
                onItemClick={addItem}
            />
        </AuthenticatedLayout>
    )
}
