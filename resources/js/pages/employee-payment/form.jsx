import React, { useEffect, useState } from 'react'
import { router, Head, Link, usePage } from '@inertiajs/react'
import { isEmpty, set } from 'lodash'

import AuthenticatedLayout from '@/layouts/default/authenticated-layout'
import {
    Card,
    Button,
    TextInput,
    FormInputDate,
    SelectModalInput,
    FormInputNumeric,
    Label,
    Spinner,
    TextareaInput,
} from '@/components/index'
import SelectSubjectSession from '../subject-session/select-modal'
import { useFetcher, useModalState } from '@/hooks'
import { formatDate, formatIDR, showToast } from '@/utils'
import { HiXMark } from 'react-icons/hi2'

export default function Form(props) {
    const {
        props: { errors },
    } = usePage()
    const { employeePayment } = props

    const modalSubjectSession = useModalState()

    const [fetch] = useFetcher()

    const [loading, setLoading] = useState(false)
    const [processing, setProcessing] = useState(false)

    const [account, set_account] = useState(null)
    const [employee, set_employee] = useState(null)
    const [payment_date, set_payment_date] = useState(null)
    const [basic_salary_per_session, set_basic_salary_per_session] =
        useState('')
    const [description, set_description] = useState('')
    const [items, set_items] = useState([])

    const handleSetDate = (date) => {
        set_payment_date(date)

        set_employee(null)
        set_basic_salary_per_session('')
        set_items([])
    }

    const handleSetEmployee = (employee) => {
        if (payment_date === null) {
            showToast('Pilih tanggal terlebih dahulu', 'error')
            return
        }
        setLoading(true)
        set_employee(employee)
        set_basic_salary_per_session(employee.basic_salary_per_session)

        // fetch the subjects session data
        fetch(
            route('api.subject-sessions.index', {
                employee_id: employee.id,
                selected_date: payment_date,
                all: 'true',
            })
        )
            .then((res) => {
                set_items(
                    res.data.map((item) => {
                        return {
                            subject_session_id: item.id,
                            subject_id: item.subject_id,
                            subject_session: item,
                            subject: item.subject,
                            person_amount: item.items_count,
                            employee_fee_per_person:
                                item.subject.employee_fee_per_person,
                            subtotal:
                                Number(item.items_count) *
                                Number(item.subject.employee_fee_per_person),
                        }
                    })
                )
            })
            .catch((err) => {
                showToast('Terjadi kesalahan', 'error')
                console.log(err)
            })
            .finally(() => setLoading(false))
    }

    const addItem = (item) => {
        const exists = items.find((i) => i.subject_session_id === item.id)
        if (exists) {
            return
        }

        set_items(
            items.concat({
                subject_session_id: item.id,
                subject_id: item.subject_id,
                subject_session: item,
                subject: item.subject,
                person_amount: item.items_count,
                employee_fee_per_person: item.subject.employee_fee_per_person,
                subtotal:
                    Number(item.items_count) *
                    Number(item.subject.employee_fee_per_person),
            })
        )
    }

    const removeItem = (index) => {
        set_items(items.filter((_, i) => i !== index))
    }

    const subtotal = items.reduce((total, item) => {
        return total + item.subtotal
    }, 0)

    const session_pay = Number(basic_salary_per_session ?? 0) * items.length
    const total = Number(subtotal) + session_pay

    const handleSubmit = () => {
        const payload = {
            account_id: account?.id,
            employee_id: employee?.id,
            payment_date,
            basic_salary_per_session,
            amount: total,
            description,
            items,
        }
        if (isEmpty(employeePayment) === false) {
            router.put(
                route('employee-payments.update', employeePayment),
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
        router.post(route('employee-payments.store'), payload, {
            onStart: () => setProcessing(true),
            onFinish: (e) => {
                setProcessing(false)
            },
        })
    }

    useEffect(() => {
        if (!isEmpty(employeePayment)) {
            set_account(employeePayment.account)
            set_employee(employeePayment.employee)
            set_payment_date(employeePayment.payment_date)
            set_basic_salary_per_session(
                employeePayment.basic_salary_per_session
            )
            set_description(employeePayment.description)
            set_items(
                employeePayment.items.map((item) => {
                    return {
                        ...item,
                        subtotal:
                            item.person_amount * item.employee_fee_per_person,
                    }
                })
            )
        }
    }, [employeePayment])

    return (
        <AuthenticatedLayout
            page={'System'}
            action={'Gaji'}
        >
            <Head title="Gaji" />

            <div>
                <Card>
                    <div className="flex flex-col gap-2 justify-between">
                        <div className="grid grid-cols-2 gap-2">
                            <FormInputDate
                                value={payment_date}
                                onChange={(date) => handleSetDate(date)}
                                label="Tanggal"
                                error={errors.payment_date}
                            />
                            <SelectModalInput
                                label="Akun Pembayaran"
                                value={account}
                                onChange={(item) => {
                                    set_account(item)
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
                            <SelectModalInput
                                label="Karyawan"
                                onChange={(item) => handleSetEmployee(item)}
                                value={employee}
                                error={errors.employee_id}
                                params={{
                                    table: 'employees',
                                    columns:
                                        'id|name|position|basic_salary_per_session',
                                    headers: 'Nama|Posisi|Gaji Pokok',
                                    orderby: 'updated_at.desc',
                                }}
                            />
                            <FormInputNumeric
                                name="basic_salary_per_session"
                                value={basic_salary_per_session}
                                onChange={(e) =>
                                    set_basic_salary_per_session(e.target.value)
                                }
                                label="Gaji Pokok"
                                error={errors.basic_salary_per_session}
                                className="-my-1"
                            />
                        </div>

                        {loading ? (
                            <div className="flex justify-center gap-2 items-center my-10">
                                <Spinner />
                                <span>Mengambil data sesi kelas . . .</span>
                            </div>
                        ) : (
                            <div className="flex flex-col gap-2">
                                <Label label="Sesi Kelas" />
                                <TextInput
                                    placeholder="pilih sesi kelas"
                                    onClick={modalSubjectSession.toggle}
                                />
                                <div className="w-full">
                                    <table className="table">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Waktu Sesi</th>
                                                <th>Kelas</th>
                                                <th>Karyawan</th>
                                                <th className="text-right">
                                                    Jumlah Member
                                                </th>
                                                <th className="text-right">
                                                    Fee Kelas
                                                </th>
                                                <th className="text-right">
                                                    Subtotal
                                                </th>
                                                <th className="w-[50px]"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {items.map((item, index) => (
                                                <tr key={index}>
                                                    <td>
                                                        {formatDate(
                                                            item.subject_session
                                                                .session_date
                                                        )}
                                                    </td>
                                                    <td>
                                                        {
                                                            item.subject_session
                                                                .training_time
                                                                .name
                                                        }
                                                    </td>
                                                    <td>{item.subject.name}</td>
                                                    <td>
                                                        {
                                                            item.subject_session
                                                                .employee.name
                                                        }
                                                    </td>
                                                    <td className="text-right">
                                                        {item.person_amount}
                                                    </td>
                                                    <td className="text-right">
                                                        {formatIDR(
                                                            item.employee_fee_per_person
                                                        )}
                                                    </td>
                                                    <td className="text-right">
                                                        {formatIDR(
                                                            item.subtotal
                                                        )}
                                                    </td>
                                                    <td>
                                                        <Button
                                                            size="xs"
                                                            onClick={() =>
                                                                removeItem(
                                                                    index
                                                                )
                                                            }
                                                        >
                                                            <HiXMark className="text-red-500" />
                                                        </Button>
                                                    </td>
                                                </tr>
                                            ))}
                                            <tr>
                                                <td colSpan={5}></td>
                                                <td className="text-right font-bold">
                                                    Subtotal
                                                </td>
                                                <td className="text-right font-bold">
                                                    {formatIDR(subtotal)}
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colSpan={5}></td>
                                                <td className="text-right font-bold">
                                                    Jumlah Sesi x Gaji Pokok
                                                </td>
                                                <td className="text-right font-bold">
                                                    {formatIDR(session_pay)}
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colSpan={5}></td>
                                                <td className="text-right font-bold">
                                                    Total
                                                </td>
                                                <td className="text-right font-bold">
                                                    {formatIDR(total)}
                                                </td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        )}
                        <TextareaInput
                            label="Keterangan"
                            value={description}
                            onChange={(e) => set_description(e.target.value)}
                            error={errors.description}
                        />
                        <div className="flex items-center">
                            <div className="flex space-x-2">
                                <Button
                                    onClick={handleSubmit}
                                    processing={processing}
                                    type="primary"
                                >
                                    Save
                                </Button>
                                <Link href={route('employee-payments.index')}>
                                    <Button type="secondary">Back</Button>
                                </Link>
                            </div>
                        </div>
                    </div>
                </Card>
            </div>
            <SelectSubjectSession
                modalState={modalSubjectSession}
                employeeId={employee?.id}
                selectedDate={payment_date}
                onItemClick={addItem}
            />
        </AuthenticatedLayout>
    )
}
