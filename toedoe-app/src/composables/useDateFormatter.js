export function useDateFormatter() {
    const formatDateLocal = (dateObj) => {
        if (!dateObj || !(dateObj instanceof Date) || isNaN(dateObj.getTime())) {
            return null;
        }
        const year = dateObj.getFullYear();
        const month = String(dateObj.getMonth() + 1).padStart(2, '0');
        const day = String(dateObj.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`;
    };

    const formatDate = (date) => {
        if (!date) return "";

        // Se já for um objeto Date, usa direto
        let dateObj = date;

        // Se for string, converte para Date
        if (typeof date === 'string') {
            // Para strings ISO como "2026-02-15T00:00:00.000000Z"
            const dateStr = date.split('T')[0];
            const [year, month, day] = dateStr.split('-').map(Number);
            dateObj = new Date(year, month - 1, day);
        }

        // Verifica se é uma data válida
        if (!(dateObj instanceof Date) || isNaN(dateObj.getTime())) {
            return "";
        }

        return new Intl.DateTimeFormat('en-GB', {
            day: 'numeric',
            month: 'short'
        }).format(dateObj);
    };

    return {
        formatDateLocal,
        formatDate
    };
}
