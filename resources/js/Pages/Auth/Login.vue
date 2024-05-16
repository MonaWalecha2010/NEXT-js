<template>
    <GuestLayout>
        <Head title="Log in"/>

        <h1 class="text-2xl my-6 font-semibold text-black">Sign in to your account</h1>

        <div class="w-full sm:max-w-md overflow-hidden sm:rounded-lg">

            <div v-if="status" class="py-1 px-4 mb-4 rounded-md font-medium text-sm text-green-600 bg-green-100" >
                {{ status }}
            </div>

            <div v-if="form.errors.email || form.errors.password" class="py-1 px-4 mb-4 rounded-md bg-red-100">
                <InputError :message="form.errors.email" class="my-2"/>
                <InputError :message="form.errors.password" class="my-2"/>
            </div>

            <form @submit.prevent="submit">
                <div class="rounded-md shadow-md bg-white">

                    <div>
                        <InputLabel for="email" value="Email" class="sr-only"/>

                        <TextInput
                            id="email"
                            v-model="form.email"
                            autocomplete="username"
                            autofocus
                            class="rounded-t-md border-b-gray-200"
                            required
                            type="email"
                            placeholder="Email Address"
                        />
                    </div>

                    <div>
                        <InputLabel for="password" value="Password" class="sr-only"/>

                        <TextInput
                            id="password"
                            v-model="form.password"
                            autocomplete="current-password"
                            class="rounded-b-md"
                            required
                            type="password"
                            placeholder="Password"
                        />
                    </div>
                </div>

                <div class="flex items-center mt-4">
                    <label class="w-full mb-0">
                        <Checkbox v-model:checked="form.remember" name="remember"/>
                        <span class="ml-2 text-sm">Remember me</span>
                    </label>
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="flex-none text-sm font-semibold jw-text__blue">
                        Forgot your password?
                    </Link>
                </div>

                <div class="flex items-center justify-end mt-6">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }"
                                   :disabled="form.processing"
                                   class="inline-flex w-full px-4 py-2.5 justify-center align-center jw-text__white jw-bg__blue capitalize">
                        <span class="icon icon__white h-4 w-4 fill-current">
                            <IconLock/>
                        </span>
                        <span class="flex-auto"><span class="inline-block -ml-4">Sign in</span></span>

                    </PrimaryButton>
                </div>
            </form>

        </div>

    </GuestLayout>
</template>
<script setup>
import {onMounted, onUnmounted} from 'vue';
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {useForm} from '@inertiajs/inertia-vue3';
import IconLock from "@/Jetworks/Icons/Lock.vue";

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

onMounted(() => {
    document.body.classList.add('authentication');
});

onUnmounted(() => {
    document.body.classList.remove('authentication');
});
</script>
