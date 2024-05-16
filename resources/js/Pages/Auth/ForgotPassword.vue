<template>
    <GuestLayout>
        <Head title="Forgot Password"/>

        <h1 class="text-2xl my-6 font-semibold text-black">Reset your password</h1>

        <div class="w-full sm:max-w-md overflow-hidden sm:rounded-lg">

            <div v-if="status" class="mb-4 font-medium text-center text-sm text-green-600">
                {{ status }}
            </div>

            <div v-if="form.errors.email" class="py-1 px-4 mb-4 bg-red-100 rounded-md">
                <InputError :message="form.errors.email" class="my-2"/>
            </div>

            <form @submit.prevent="submit">
                <div class="rounded-md shadow-md bg-white">
                    <InputLabel for="email" value="Email" class="sr-only"/>
                    <TextInput
                        id="email"
                        v-model="form.email"
                        autocomplete="username"
                        autofocus
                        class="block w-full outline-0 border-transparent rounded-md border-b-gray-200 text-sm"
                        required
                        type="email"
                        placeholder="Email Address"
                    />
                </div>

                <div class="flex w-full justify-end align-end mt-4">
                    <Link
                        :href="route('password.request')"
                        class="text-sm font-semibold jw-text__blue">
                        Login to your account
                    </Link>
                </div>

                <div class="flex items-center justify-end mt-6">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }"
                                   :disabled="form.processing"
                                   class="w-full px-4 py-2.5 justify-center align-center jw-text__white jw-bg__blue capitalize">
                        Send Password Reset Link
                    </PrimaryButton>
                </div>
            </form>

        </div>

    </GuestLayout>
</template>
<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {useForm} from '@inertiajs/inertia-vue3';
import {onMounted, onUnmounted} from "vue";

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};

onMounted(() => {
    document.body.classList.add('authentication');
});

onUnmounted(() => {
    document.body.classList.remove('authentication');
});
</script>
