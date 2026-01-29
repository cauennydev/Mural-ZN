/**
 * Utilitário Data/Hora - Versão ES6+ Moderna
 * Script que mostra Data e Hora do Computador Local no seu Web Site.
 * 
 * Modo de uso:
 * <script type="module" src="clock.js"><\/script>
 * <span id="clock_tm">Hora Atual!</span><br>
 * <span id="clock_dt">Data atual!</span>
 * 
 * <script type="module">
 *   import { startClock } from './clock.js';
 *   startClock('d/m/Y', 'H:i:s');
 * <\/script>
 */

// ============ Variáveis do Módulo ============

/** Array contendo partes da data/hora: [ano, mês, dia, hora, minuto, segundo] */
let dateTimeParts = [];

/** ID do timeout para atualização do relógio */
let clockTimeout = null;

/** Formato de hora (ex: 'H:i:s') */
let timeFormat = '0';

/** Formato de data (ex: 'd/m/Y') */
let dateFormat = '0';

// ============ Funções Utilitárias ============

/**
 * Calcula o fuso horário
 * @param {Date} data - Data para calcular
 * @param {number} offset - Offset do fuso horário em horas
 * @returns {Date} - Nova data com o fuso aplicado
 */
const calculateTimezone = (data, offset) => {
	const millisecondsWithUtc = data.getTime() + (data.getTimezoneOffset() * 60000);
	return new Date(millisecondsWithUtc + (3600000 * offset));
};

/**
 * Padding de números com zero à esquerda
 * @param {number} value - Valor a ser formatado
 * @returns {string} - Valor formatado com dois dígitos
 */
const padNumber = (value) => String(value).padStart(2, '0');

/**
 * Formata a data de acordo com o formato especificado
 * Formatos suportados: Y (ano), m (mês), d (dia)
 * @returns {string} - Data formatada
 */
const formatDate = () => {
	let formatted = dateFormat
		.replace(/Y/g, dateTimeParts[0])
		.replace(/m/g, dateTimeParts[1])
		.replace(/d/g, dateTimeParts[2]);
	return formatted;
};

/**
 * Formata a hora de acordo com o formato especificado
 * Formatos suportados: H (hora), i (minuto), s (segundo)
 * @returns {string} - Hora formatada
 */
const formatTime = () => {
	let formatted = timeFormat
		.replace(/H/g, dateTimeParts[3])
		.replace(/i/g, dateTimeParts[4])
		.replace(/s/g, dateTimeParts[5]);
	return formatted;
};

/**
 * Obtém os elementos DOM e atualiza seu conteúdo
 * Seguro: usa textContent em vez de innerHTML
 */
const updateDOMElements = () => {
	const dateElement = document.getElementById('clock_dt');
	const timeElement = document.getElementById('clock_tm');

	if (dateElement) {
		dateElement.textContent = formatDate();
	}

	if (timeElement) {
		timeElement.textContent = formatTime();
	}
};

// ============ Função Principal de Atualização ============

/**
 * Atualiza o relógio com os valores atuais de data/hora
 * Executada a cada segundo através de setTimeout
 */
const updateClock = () => {
	// Limpar timeout anterior
	if (clockTimeout) {
		clearTimeout(clockTimeout);
	}

	// Obter data/hora atual
	const currentDate = new Date();

	// Preencher array com valores formatados
	dateTimeParts = [
		currentDate.getFullYear(),
		padNumber(currentDate.getMonth() + 1),
		padNumber(currentDate.getDate()),
		padNumber(currentDate.getHours()),
		padNumber(currentDate.getMinutes()),
		padNumber(currentDate.getSeconds())
	];

	// Atualizar elementos DOM
	updateDOMElements();

	// Agendar próxima atualização em 1 segundo
	clockTimeout = setTimeout(updateClock, 1000);
};

// ============ API Pública ============

/**
 * Inicia o relógio com os formatos especificados
 * @param {string} df - Formato de data (ex: 'd/m/Y', 'd-m-Y')
 * @param {string} tf - Formato de hora (ex: 'H:i:s', 'H:i')
 */
export const startClock = (df, tf) => {
	dateFormat = df;
	timeFormat = tf;
	clockTimeout = setTimeout(updateClock, 500);
};

/**
 * Para o relógio e limpa o timeout
 */
export const stopClock = () => {
	if (clockTimeout) {
		clearTimeout(clockTimeout);
		clockTimeout = null;
	}
};

/**
 * Define um cookie (função preservada para compatibilidade)
 * @param {string} name - Nome do cookie
 * @param {string} val - Valor do cookie
 * @param {boolean} first - Se é o primeiro cookie
 * @deprecated Considere usar Web Storage (localStorage/sessionStorage) em vez de cookies
 */
export const setCookie = (name, val, first = true) => {
	const separator = first ? '' : '; ';
	document.cookie = `${separator}${name}=${val}`;
};

// ============ Compatibilidade com Código Antigo ============
// Se o script for carregado sem module, expor funções globais

if (typeof window !== 'undefined' && !window.startClock) {
	window.startClock = startClock;
	window.stopClock = stopClock;
	window.setCookie = setCookie;
}
