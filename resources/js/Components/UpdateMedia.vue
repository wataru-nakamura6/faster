<script setup>
import { onBeforeUnmount, onMounted, reactive, watch } from 'vue';
import { useToast } from 'vue-toast-notification';
import axios from 'axios';

const props = defineProps({
    site: Object,
    isOpen: Boolean
});

const emits = defineEmits(['isClose']);

const UploadSite = reactive({
    id: null,
    url: '',
    upload_status: 0
});

watch(
    () => props.site,
    (newSite) => {
        if (newSite) {
            UploadSite.id = newSite.id;
            UploadSite.url = newSite.url;
            UploadSite.upload_status = newSite.upload_status;
        }
    },
    { immediate: true }
);
watch(
    () => props.isOpen,
    (open) => {
        if (open) {
            UploadSite.id = props.site?.id || null;
            UploadSite.url = props.site?.url || '';
            UploadSite.upload_status = props.site?.upload_status || 0;

            window.removeEventListener('message', messageHandler);
            window.addEventListener('message', messageHandler);
        }
    }
);

const openTargetSiteAndPostMessage = async (type = 'button_clicked') => {
    try {
        const urlObj = new URL(UploadSite.url);
        urlObj.searchParams.set('from_admin', 'true');
        const fullUrl = urlObj.toString();

        const cacheBuster = `cb=${Date.now()}`;
        const fullUrlWithCb = fullUrl.includes('?') ? `${fullUrl}&${cacheBuster}` : `${fullUrl}?${cacheBuster}`;
        const openedWindow = window.open(fullUrlWithCb, '_blank');
        if (!openedWindow) throw new Error('ウィンドウのオープンに失敗しました');

        const targetOrigin = urlObj.origin;

        // メッセージを受け取るイベントリスナー
        const onMessage = (event) => {
            if (event.origin !== targetOrigin) return;

            // 子ウィンドウから準備完了メッセージを受け取ったらpostMessage送信
            if (event.data?.type === 'ready') {
                openedWindow.postMessage(
                    {
                        type,
                        data: {
                            message: '管理画面からの通知です',
                            siteId: UploadSite.id,
                        },
                    },
                    targetOrigin
                );
                console.log(`postMessage (${type}) を送信しました`);
            }
            // 子ウィンドウからの最適化完了通知を受け取ったら閉じる
            if (event.data?.type === 'completed') {
                console.log('最適化処理完了通知を受信しました');
                if (!openedWindow.closed) {
                    openedWindow.close();
                    console.log('子ウィンドウを閉じました');
                }
                // イベントリスナーはもう不要なら解除
                window.removeEventListener('message', onMessage);
            }
        };

        window.addEventListener('message', onMessage);

        // ウィンドウが閉じられたら監視停止
        const checkInterval = setInterval(() => {
            if (!openedWindow || openedWindow.closed) {
                clearInterval(checkInterval);
                window.removeEventListener('message', onMessage);
                console.warn('対象ウィンドウが閉じられました');
            }
        }, 500);

    } catch (err) {
        console.error('ウィンドウのオープンまたは postMessage に失敗:', err);
        useToast().error('通信に失敗しました', { timeout: 5000 });
        await saveStatusToDB(4);
        emits('isClose');
    }
};

const submitRoute = () => openTargetSiteAndPostMessage('button_clicked');

const messageHandler = (event) => {
    const { type, status, failedMedia, message, logs } = event.data || {};
    if (type !== 'completed') return;
    console.log(message)

    switch (status) {
        case 'success':
            useToast().success('アップロードがすべて完了しました。', { timeout: 5000 });
            saveStatusToDB(1);
            break;

        case 'partial_fail':
            useToast().error(`一部のアップロードに失敗したため中断しました。`, { timeout: 5000 });
            saveStatusToDB(2);
            break;

        case 'failed_to_fetch_media':
            useToast().error('画像・動画の取得に失敗しました。', { timeout: 5000 });
            saveStatusToDB(3);
            break;

        default:
            useToast().error('不明なエラーが発生しました。', { timeout: 5000 });
            break;
    }
    const complete_message = (failedMedia !== null)
        ? message + ':' + JSON.stringify(failedMedia)
        : message;
    saveUploadLog(complete_message);

    if (logs) {
        saveUploadLog(JSON.stringify(logs));
    }

    emits('isClose');
    window.removeEventListener('message', messageHandler);
};

onMounted(() => {
    window.addEventListener('message', messageHandler);
});

onBeforeUnmount(() => {
    window.removeEventListener('message', messageHandler);
});

const saveStatusToDB = async (status) => {
    try {
        await axios.post('/site/site-status', {
            site_id: UploadSite.id,
            upload_status : status,
        });
    } catch (error) {
        console.error('ステータス保存に失敗しました', error);
    }
};
const saveUploadLog = async (message) => {
    try {
        await axios.post('/site/upload-log', {
            site_id: UploadSite.id,
            message,
        });
    } catch (error) {
        console.error('ログ保存に失敗しました', error);
    }
};
</script>

<template>
    <div v-if="isOpen" class="modal_overlay">
        <div class="modal_content">
            <h2>以下のURLを最適化しますか？</h2>
            <p>{{ UploadSite.url }}</p>
            <div class="notice">
                <p>※登録前に以下を確認お願いします</p>
                <ul>
                    <li>・高速化タグは head タグ内に埋め込み済みですか？</li>
                    <li>・サイトは完成した状態ですか？</li>
                </ul>
            </div>

            <div class="actions">
                <button @click="submitRoute" class="submit">最適化する</button>
                <button @click="$emit('isClose')">キャンセル</button>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.modal_overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.4);
    display: flex;
    justify-content: center;
    align-items: center;

    .modal_content {
        background: white;
        padding: 30px;
        border-radius: 8px;
        width: 500px;

        h2{
            padding: 15px;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }

        p{
            text-align: center;
            padding: 5px 0;
        }

        input{
            border: 1px solid #E0E0E0;
            border-radius: 50px;
            margin-bottom: 5px;
            width: 100%;
        }

        .actions {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;

            button{
                background: #0f0f0f;
                width: 100px;
                padding: 10px;
                color: #fff;
                border-radius: 50px;

                &.submit{
                    background: #930000;
                }
            }
        }
    }
}
</style>
