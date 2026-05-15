export function useInputClass() {
    function inputCls(hasError = false) {
        return [
            'mt-1.5 block w-full rounded-lg border px-3.5 py-2.5 text-sm shadow-sm transition-colors',
            'focus:outline-none focus:ring-1',
            'dark:bg-slate-700 dark:text-white dark:placeholder:text-slate-500',
            hasError
                ? 'border-red-400 focus:border-red-400 focus:ring-red-400 dark:border-red-500'
                : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:focus:border-indigo-400 dark:focus:ring-indigo-400',
        ].join(' ')
    }

    return { inputCls }
}
