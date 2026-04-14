export function showToast(message: string, type: string): void {
    if (typeof window !== 'undefined' && (window as any).toast) {
        (window as any).toast(message, type);
    }
}
