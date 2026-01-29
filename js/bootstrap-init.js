/**
 * Bootstrap 5.3+ - Inicialização moderna de componentes
 * Substitui os vários plugins individuais do Bootstrap 2
 * 
 * Uso: Adicione este script APÓS carregar bootstrap.bundle.min.js
 * <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 * <script src="js/bootstrap-init.js"></script>
 */

(() => {
	'use strict';

	/**
	 * Inicializa Tooltips - elementos com data-bs-toggle="tooltip"
	 */
	const initTooltips = () => {
		const tooltipTriggerList = [].slice.call(
			document.querySelectorAll('[data-bs-toggle="tooltip"]')
		);
		tooltipTriggerList.map(tooltipTriggerEl => {
			return new bootstrap.Tooltip(tooltipTriggerEl);
		});
	};

	/**
	 * Inicializa Popovers - elementos com data-bs-toggle="popover"
	 */
	const initPopovers = () => {
		const popoverTriggerList = [].slice.call(
			document.querySelectorAll('[data-bs-toggle="popover"]')
		);
		popoverTriggerList.map(popoverTriggerEl => {
			return new bootstrap.Popover(popoverTriggerEl);
		});
	};

	/**
	 * Inicializa Modais com fechar automático
	 */
	const initModals = () => {
		const myModalList = document.querySelectorAll('.modal');
		myModalList.forEach(myModalEl => {
			new bootstrap.Modal(myModalEl, {
				keyboard: true,
				backdrop: true,
				focus: true
			});
		});
	};

	/**
	 * Inicializa Dropdowns
	 */
	const initDropdowns = () => {
		const dropdownElementList = [].slice.call(
			document.querySelectorAll('[data-bs-toggle="dropdown"]')
		);
		dropdownElementList.map(dropdownToggleEl => {
			return new bootstrap.Dropdown(dropdownToggleEl);
		});
	};

	/**
	 * Inicializa Abas (Tabs)
	 */
	const initTabs = () => {
		const triggerTabList = [].slice.call(
			document.querySelectorAll('a[data-bs-toggle="tab"], a[data-bs-toggle="pill"]')
		);
		triggerTabList.forEach(triggerEl => {
			const tab = new bootstrap.Tab(triggerEl);
			triggerEl.addEventListener('click', e => {
				e.preventDefault();
				tab.show();
			});
		});
	};

	/**
	 * Inicializa Collapses (Acordeões)
	 */
	const initCollapses = () => {
		const collapseElementList = [].slice.call(
			document.querySelectorAll('[data-bs-toggle="collapse"]')
		);
		collapseElementList.map(collapseEl => {
			return new bootstrap.Collapse(collapseEl, {
				toggle: false
			});
		});
	};

	/**
	 * Inicializa Scrollspy
	 */
	const initScrollspy = () => {
		const scrollSpyElements = document.querySelectorAll('[data-bs-spy="scroll"]');
		scrollSpyElements.forEach(el => {
			new bootstrap.ScrollSpy(el, {
				target: el.getAttribute('data-bs-target')
			});
		});
	};

	/**
	 * Inicializa Carrossel
	 */
	const initCarousels = () => {
		const carouselElements = document.querySelectorAll('.carousel');
		carouselElements.forEach(el => {
			new bootstrap.Carousel(el, {
				interval: 5000,
				keyboard: true,
				pause: 'hover',
				ride: 'carousel',
				touch: true,
				wrap: true
			});
		});
	};

	/**
	 * Inicializa Alertas com fechar automático
	 */
	const initAlerts = () => {
		const alerts = document.querySelectorAll('.alert-dismissible button.btn-close');
		alerts.forEach(alert => {
			alert.addEventListener('click', function() {
				const parent = this.closest('.alert');
				if (parent) {
					const bsAlert = new bootstrap.Alert(parent);
					bsAlert.close();
				}
			});
		});
	};

	/**
	 * Validação de Formulários
	 */
	const initFormValidation = () => {
		const forms = document.querySelectorAll('.needs-validation');
		forms.forEach(form => {
			form.addEventListener('submit', e => {
				if (!form.checkValidity()) {
					e.preventDefault();
					e.stopPropagation();
				}
				form.classList.add('was-validated');
			}, false);
		});
	};

	/**
	 * Função principal de inicialização
	 */
	const init = () => {
		document.addEventListener('DOMContentLoaded', () => {
			initTooltips();
			initPopovers();
			initModals();
			initDropdowns();
			initTabs();
			initCollapses();
			initScrollspy();
			initCarousels();
			initAlerts();
			initFormValidation();
			console.log('✓ Bootstrap 5.3 components initialized');
		});
	};

	// Chamar inicialização
	init();

	// Exportar funções para uso global
	window.Bootstrap5 = {
		initTooltips,
		initPopovers,
		initModals,
		initDropdowns,
		initTabs,
		initCollapses,
		initScrollspy,
		initCarousels,
		initAlerts,
		initFormValidation
	};

})();
