<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[var(--primary-color)] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:opacity-90 focus:bg-[var(--primary-color)] active:opacity-100 focus:outline-hidden focus:ring-2 focus:ring-[var(--primary-color)] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
