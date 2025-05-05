<script setup>
import {Link, usePage} from "@inertiajs/vue3";
import DropdownLink from "@/Components/Laravel/DropdownLink.vue";
import Dropdown from "@/Components/Laravel/Dropdown.vue";

const user = usePage().props.auth.user;

</script>

<template>
    <header>
        <div class="inner_wrap">
            <div class="logo_wrap">
                <Link :href="route('top')">
                    LP高速化
                </Link>
            </div>
            <Dropdown>
                <template #trigger>
                    <button>
                        {{ user.name }}
                    </button>
                </template>

                <template #content>
                    <DropdownLink
                        :href="route('profile.edit')"
                    >
                        登録情報編集
                    </DropdownLink>
                    <DropdownLink
                        :href="route('logout')"
                        method="post"
                        as="button"
                    >
                        ログアウト
                    </DropdownLink>
                </template>
            </Dropdown>
        </div>
    </header>
</template>

<style lang="scss" scoped>
header {
    border-bottom: 1px solid #E0E0E0;
    width: 100%;
    background: #fff;

    .inner_wrap {
        padding: 16px 80px;
        max-width: 1240px;
        margin: auto;
        display: flex;
        justify-content: space-between;
        font-weight: bold;
        font-size: 14px;
    }
}
</style>
