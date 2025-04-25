import React, { useEffect, useState } from 'react'
import { Head, Link } from '@inertiajs/react'
import { isEmpty } from 'lodash'

import AuthenticatedLayout from '@/layouts/default/authenticated-layout'
import {
    Card,
    Button,
    TextInput,
    SelectModalInput,
    Label,
    TextareaInput,
} from '@/components/index'
import { formatDate, formatIDR } from '@/utils'
import HasPermission from '@/components/common/has-permission'
import { HiPencil, HiPrinter } from 'react-icons/hi2'

export default function Form(props) {
    const { employeePayment } = props

    const [account, set_account] = useState(null)
    const [employee, set_employee] = useState(null)
    const [payment_date, set_payment_date] = useState(null)
    const [payment_date_end, set_payment_date_end] = useState(null)
    const [basic_salary_per_session, set_basic_salary_per_session] =
        useState('')
    const [description, set_description] = useState('')
    const [items, set_items] = useState([])

    const subtotal = items.reduce((total, item) => {
        return total + item.subtotal
    }, 0)

    const session_pay = Number(basic_salary_per_session ?? 0) * items.length
    const total = Number(subtotal) + session_pay

    useEffect(() => {
        if (!isEmpty(employeePayment)) {
            set_account(employeePayment.account)
            set_employee(employeePayment.employee)
            set_payment_date(employeePayment.payment_date)
            set_payment_date_end(employeePayment.payment_date_end)
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
                    <div className="flex flex-row gap-2">
                        <HasPermission p="update-employee-payment">
                            <Link
                                href={route(
                                    'employee-payments.edit',
                                    employeePayment.id
                                )}
                            >
                                <Button>
                                    <HiPencil />
                                    <span>Edit</span>
                                </Button>
                            </Link>
                        </HasPermission>
                        <a
                            href={route(
                                'employee-payments.print',
                                employeePayment.id
                            )}
                            target="_blank"
                        >
                            <Button>
                                <HiPrinter />
                                <span>Cetak Slip</span>
                            </Button>
                        </a>
                    </div>
                    <div className="flex flex-col gap-2 justify-between">
                        <div className="grid grid-cols-2 gap-2">
                            <TextInput
                                value={employeePayment.periode_text}
                                readOnly={true}
                                label="Periode"
                            />
                            <TextInput
                                label="Akun Pembayaran"
                                value={account?.name}
                                readOnly={true}
                            />
                            <TextInput
                                label="Karyawan"
                                value={employee?.name}
                                readOnly={true}
                            />
                            <TextInput
                                label="Gaji Pokok"
                                value={formatIDR(basic_salary_per_session)}
                                readOnly={true}
                            />
                        </div>
                        <div className="flex flex-col gap-2">
                            <Label label="Sesi Kelas" />
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
                                                            .training_time.name
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
                                                    {formatIDR(item.subtotal)}
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
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <TextareaInput
                            label="Keterangan"
                            value={description}
                            readOnly={true}
                        />
                        <div className="flex items-center">
                            <div className="flex space-x-2">
                                <Link href={route('employee-payments.index')}>
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
