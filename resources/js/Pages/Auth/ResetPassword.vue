<template>
    <GuestLayout>
        <Head title="Reset Password"/>

        <h1 class="text-2xl my-6 font-semibold text-black">Set a new password</h1>

        <div class="w-full sm:max-w-md overflow-hidden sm:rounded-lg">

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <div v-if="form.errors.email || form.errors.password || form.errors.password_confirmation" class="py-1 px-4 mb-4 bg-red-100 rounded-md">
                <InputError :message="form.errors.email" class="my-2"/>
                <InputError :message="form.errors.password" class="my-2"/>
                <InputError :message="form.errors.password_confirmation" class="my-2"/>
            </div>

            <form @submit.prevent="submit">

                <div class="mb-4 rounded-md shadow-md bg-white">

                    <div>
                        <InputLabel for="email" value="Email" class="sr-only"/>
                        <TextInput
                            id="email"
                            v-model="form.email"
                            autocomplete="username"
                            class="rounded-md border-b-gray-200"
                            required
                            type="email"
                            placeholder="Enter your email"
                        />
                    </div>

                </div>

                <div class="rounded-md shadow-md bg-white">

                    <div>
                        <InputLabel for="password" value="Password" class="sr-only"/>
                        <TextInput
                            id="password"
                            v-model="form.password"
                            autocomplete="current-password"
                            class="rounded-t-md border-b-gray-200"
                            required
                            type="password"
                            placeholder="Enter a new password"
                        />
                    </div>

                    <div>
                        <InputLabel for="password_confirmation" value="Confirm Password" class="sr-only"/>
                        <TextInput
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            autocomplete="new-password"
                            class="rounded-b-md"
                            required
                            type="password"
                            placeholder="Confirm new password"
                        />
                    </div>

                </div>

                <div class="flex items-center mt-6">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }"
                                   :disabled="form.processing"
                                   class="inline-flex w-full px-4 py-2.5 justify-center align-center jw-text__white jw-bg__blue capitalize">
                        <span class="capitalize"><span class="inline-block -ml-4">Confirm New Password</span></span>

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

const props = defineProps({
    email: String,
    token: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

onMounted(() => {
    document.body.classList.add('authentication');
});

onUnmounted(() => {
    document.body.classList.remove('authentication');
});
</script>
