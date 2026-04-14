import type { Ref } from 'vue';
import { ref } from 'vue';

type StreamState = 'idle' | 'connecting' | 'streaming' | 'finishing' | 'error';

export function useConversationStream(streamUrl: string, onComplete?: (response: string) => void) {
    const streamingResponse: Ref<string> = ref('');
    const streamState: Ref<StreamState> = ref('idle');
    const streamError: Ref<string | null> = ref(null);
    const reconnectAttempts: Ref<number> = ref(0);
    const maxReconnectAttempts = 3;
    let eventSource: EventSource | null = null;
    let reconnectTimeoutId: number | null = null;
    let shouldReconnect = false;
    /** Invalidates deferred EventSource handlers so they cannot tear down a newer connection. */
    let activeStreamId = 0;

    const clearReconnectTimeout = (): void => {
        if (reconnectTimeoutId !== null) {
            window.clearTimeout(reconnectTimeoutId);
            reconnectTimeoutId = null;
        }
    };

    const closeConnection = (): void => {
        if (!eventSource) {
            return;
        }

        eventSource.close();
        eventSource = null;
    };

    const stopStream = (): void => {
        shouldReconnect = false;
        clearReconnectTimeout();
        closeConnection();
        activeStreamId += 1;
        streamState.value = 'idle';
        streamError.value = null;
        streamingResponse.value = '';
        reconnectAttempts.value = 0;
    };

    const clearStreamingResponse = (): void => {
        streamingResponse.value = '';
    };

    const connect = (): void => {
        streamState.value = 'connecting';
        clearReconnectTimeout();
        closeConnection();
        const streamId = ++activeStreamId;
        const source = new EventSource(streamUrl);
        eventSource = source;

        source.onmessage = (event) => {
            if (streamId !== activeStreamId) {
                return;
            }

            try {
                const payload = JSON.parse(event.data) as {
                    type?: string;
                    delta?: string;
                };

                switch (payload.type) {
                    case 'stream_start':
                        streamState.value = 'streaming';
                        streamError.value = null;
                        reconnectAttempts.value = 0;
                        break;
                    case 'text_delta':
                        streamState.value = 'streaming';
                        streamingResponse.value += payload.delta ?? '';
                        break;
                    case 'stream_end': {
                        streamState.value = 'finishing';
                        const text = streamingResponse.value.trim();

                        shouldReconnect = false;
                        clearReconnectTimeout();

                        if (onComplete && text !== '') {
                            onComplete(text);
                        }

                        closeConnection();
                        streamingResponse.value = '';
                        streamState.value = 'idle';
                        break;
                    }
                    default:
                        break;
                }
            } catch {
                // Ignore non-JSON keep-alive events.
            }
        };

        source.onerror = () => {
            if (streamId !== activeStreamId) {
                return;
            }

            closeConnection();

            if (!shouldReconnect) {
                if (streamState.value === 'connecting' || streamState.value === 'streaming' || streamState.value === 'finishing') {
                    streamState.value = 'idle';
                }

                return;
            }

            if (reconnectAttempts.value < maxReconnectAttempts) {
                reconnectAttempts.value += 1;
                streamState.value = 'connecting';
                const backoffDelay = 1000 * Math.pow(2, reconnectAttempts.value - 1);
                reconnectTimeoutId = window.setTimeout(() => {
                    connect();
                }, backoffDelay);
                return;
            }

            shouldReconnect = false;
            clearReconnectTimeout();
            streamState.value = 'error';
            streamError.value = 'Unable to connect to the AI model after multiple attempts. Connection closed.';
        };
    };

    const startStreaming = (): void => {
        stopStream();
        shouldReconnect = true;
        connect();
    };

    return {
        streamingResponse,
        streamState,
        streamError,
        reconnectAttempts,
        maxReconnectAttempts,
        startStreaming,
        clearStreamingResponse,
        stopStream,
    };
}
