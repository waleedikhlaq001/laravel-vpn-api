<template>
    <div class="flex flex-col items-center justify-center min-h-[90vh]">
        <div class="w-full max-w-[50rem] text-center">
            <p>
                Before proceeding, please check your email for a verification
                link.
            </p>
            <p class="mt-3 mb-3">If you did not receive the email,</p>
            <form
                @submit.prevent="form.post('/email/verification-notification')"
                method="POST"
            >
                <button type="submit" class="btn-primary">
                    Click here to request another
                </button>
            </form>
        </div>
    </div>
</template>
<script>
import { useForm } from "@inertiajs/vue3";

export default {
    setup() {
        const form = useForm({
            email: "",
            password: "",
        });
        return {
            form,
        };
    },
};
</script>
