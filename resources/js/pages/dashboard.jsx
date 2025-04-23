import React from 'react'
import { Head } from '@inertiajs/react'

import AuthenticatedLayout from '@/layouts/default/authenticated-layout'
import { formatIDR } from '@/utils'

export default function Dashboard(props) {
    return (
        <AuthenticatedLayout
            page={'Dashboard'}
            action={''}
        >
            <Head title="Dashboard" />

            <div>
                <div className="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 py-2 gap-2">
                    <div className="stats shadow flex-1 bg-base-100 border border-base-300">
                        <div className="stat">
                            <div className="stat-title">Roles</div>
                            <div className="stat-value text-primary">
                                {props.role_count}{' '}
                            </div>
                        </div>
                    </div>
                    <div className="stats shadow flex-1 bg-base-100 border border-base-300">
                        <div className="stat">
                            <div className="stat-title">Users</div>
                            <div className="stat-value text-primary">
                                {props.user_count}
                            </div>
                        </div>
                    </div>
                    <div className="stats shadow flex-1 bg-base-100 border border-base-300">
                        <div className="stat">
                            <div className="stat-title">Member</div>
                            <div className="stat-value text-primary">
                                {props.member_count}
                            </div>
                        </div>
                    </div>
                    <div className="stats shadow flex-1 bg-base-100 border border-base-300">
                        <div className="stat">
                            <div className="stat-title">Membership</div>
                            <div className="stat-value text-primary">
                                {props.membership_count}
                            </div>
                        </div>
                    </div>
                    {props.accounts.map((account) => (
                        <div
                            className="stats shadow flex-1 bg-base-100 border border-base-300"
                            key={account.id}
                        >
                            <div className="stat">
                                <div className="stat-title">
                                    Saldo {account.name}
                                </div>
                                <div className="stat-value text-primary">
                                    {formatIDR(account.balance_amount)}
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </AuthenticatedLayout>
    )
}
