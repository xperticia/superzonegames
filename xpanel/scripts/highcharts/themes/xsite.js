/**
 * XSITE theme for Highcharts JS
 * @author Walter E. Irahola
 */
Highcharts.theme = {
	lang: {
		contextButtonTitle: 'Menu contextual de Grafico',
		printChart: 'Imprimir Grafico',
		downloadJPEG: 'Descargar imagen JPG',
		downloadPDF: 'Descargar documento PDF',
		downloadPNG: 'Descargar imagen PNG',
		downloadSVG: 'Descargar imagen vectorial SVG',
		loading: 'Cargando..'
	},
	colors: ["rgb(79,129,189)", "rgb(192,80,77)", "rgb(155,187,89)", "rgb(128,100,162)", "rgb(75,172,198)", "rgb(247,150,70)", "rgb(192,0,0)","rgb(255,0,0)", "rgb(255,192,0)", "rgb(255,255,0)", "rgb(146,208,80)"],
	chart: {
		backgroundColor: '#fff',
		borderWidth: 1,
		borderRadius: 5,
		borderColor: '#909090',
		plotBackgroundColor: null,
		plotShadow: false,
		plotBorderWidth: 0
	},
	credits: {
		enabled: true,
		text: 'xperticia.com',
		href: 'http://www.xperticia.com',
		style: {
			color: '#0069D6',
			fontSize: '12px',
			cursor: 'pointer',
			fontWeight: 'bold'
		},
		position: {
			y: -10
		}
	},
	subtitle: {
		style: {
			color: '#3E576F'
		}
	},
	plotOptions: {
		series: {
			shadow: true
		},
		line: {
			dataLabels: {
				enabled: true
			}
		},
		spline: {
			dataLabels: {
				enabled: true
			}
		},
		column: {
			dataLabels: {
				enabled: true
			}
		},
		bar: {
			dataLabels: {
				enabled: true
			}
		},
	},

	// special colors for some of the demo examples
	legendBackgroundColor: 'rgba(48, 48, 48, 0.8)',
	legendBackgroundColorSolid: 'rgb(70, 70, 70)',
	dataLabelsColor: '#444',
	textColor: '#E0E0E0',
	maskColor: 'rgba(255,255,255,0.3)',
};

// Apply the theme
var highchartsOptions = Highcharts.setOptions(Highcharts.theme);
