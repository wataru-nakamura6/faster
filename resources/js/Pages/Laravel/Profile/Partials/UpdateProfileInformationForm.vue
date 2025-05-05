<script setup>
import InputError from '@/Components/Laravel/InputError.vue';
import InputLabel from '@/Components/Laravel/InputLabel.vue';
import PrimaryButton from '@/Components/Laravel/PrimaryButton.vue';
import TextInput from '@/Components/Laravel/TextInput.vue';
import {Link, useForm, usePage} from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <div
            class="content-wrap"
        >
            <header>
                <h2 class="text-lg font-medium">
                    登録情報
                </h2>

                <p class="mt-1">
                    アカウントのメールアドレスを更新します。
                </p>
            </header>

            <form
                @submit.prevent="form.patch(route('profile.update'))"
                class="mt-6 space-y-6 max-w-xl"
            >
                <div>
                    <InputLabel for="name" value="登録名"/>

                    <div class="text-sm mt-1 block w-full">
                        {{ user.name }}
                    </div>
                </div>

                <div>
                    <InputLabel for="email" value="メールアドレス"/>

                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                        autocomplete="username"
                    />

                    <InputError class="mt-2" :message="form.errors.email"/>
                </div>

                <div class="flex items-center gap-4">
                    <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                    <Transition
                        enter-active-class="transition ease-in-out"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out"
                        leave-to-class="opacity-0"
                    >
                        <p
                            v-if="form.recentlySuccessful"
                            class="text-sm text-gray-600"
                        >
                            変更完了
                        </p>
                    </Transition>
                </div>
            </form>
        </div>
    </section>
</template>

<style>

</style>
