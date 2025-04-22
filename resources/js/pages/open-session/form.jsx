import { Button, Card, TextInput } from '@/components/index'
import { useForm } from '@inertiajs/react'

export default function Form() {
    const { data, setData, post, processing, errors } = useForm({ code: '' })

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

    const handleSubmit = () => {
        post(route('open-sessions.store'))
    }

    return (
        <Card>
            <div className="form-control space-y-2.5">
                <TextInput
                    name="code"
                    value={data.code}
                    onChange={handleOnChange}
                    label="Member ID"
                    placeholder="masukan Member ID atau no.telp"
                    error={errors.code}
                />

                <div className="flex items-center space-x-2 mt-4">
                    <Button
                        onClick={handleSubmit}
                        processing={processing}
                        type="primary"
                    >
                        Save
                    </Button>
                </div>
            </div>
        </Card>
    )
}
