export function useProfileFieldClass() {
    function fieldCls(hasError = false) {
        return [
            'mt-1.5 block w-full rounded-[9px] border bg-field px-[13px] py-[10px] text-[13.5px] text-ink outline-none transition-colors',
            'dark:bg-field-dark dark:text-ink-dark',
            hasError
                ? 'border-red-400 focus:border-red-400 dark:border-red-500'
                : 'border-fieldedge focus:border-accent dark:border-fieldedge-dark',
        ].join(' ')
    }

    return { fieldCls }
}
