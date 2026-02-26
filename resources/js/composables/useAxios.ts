import { useAxios as baseUseAxios } from '@vueuse/integrations/useAxios';
import type { UseAxiosOptions } from '@vueuse/integrations/useAxios';
import axios, { type AxiosRequestConfig } from 'axios';

const useAxios = <T = any>(
    url: string,
    axiosOptions: AxiosRequestConfig = {},
    data: Record<string, any> = {},
    vueUseOptions: UseAxiosOptions<T> = {},
) => {
    const instance = axios.create({
        baseURL: '/', // @todo get from env.
    });

    return baseUseAxios<T>(
        url,
        {
            method: 'POST',
            data,
            ...axiosOptions,
        },
        instance,
        {
            immediate: true,
            shallow: true,
            abortPrevious: true,
            resetOnExecute: false,
            ...vueUseOptions,
        },
    );
};

export { useAxios };
