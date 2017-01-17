var map = undefined;
var waitMap = $.Deferred();
var basemap = "topo";

function carregaMapa() {
	setLoaderState(true);

	waitMap = $.Deferred();

	if (map) {
		try {
			map.removeAllLayers();
		} catch (e) {console.log("Não foi possivel limpar o Mapa");}
		
		map.setBasemap(basemap);
		
		waitMap.resolve();
	} else {
		try {
			require([ "esri/map", "dojo/dom", "dojo/on","dijit/layout/BorderContainer", "dijit/layout/ContentPane", "dojo/domReady!" ],
					function(Map, dom, on, BorderContainer, ContentPane) {

				if (map == undefined)
					map = new Map("map", {
						autoResize : true,
						basemap : basemap,
						sliderPosition : "top-left",
						showAttribution : false,
						minZoom : 0,
						maxZoom : 10,
						logo: false
					});

				map.on("update-start", function (event) {
					$("#map-loader-container").fadeIn(100);
				});

				map.on("update-end", function (event) {
					$("#map-loader-container").fadeOut(100);
				});
				
				waitMap.resolve();
			});
		} catch (e) {
			console.log(e);
		}
	}
}

function montarMapa(dados){
	carregaMapa();

	$.when(waitMap).done(function (){
		while (!map)
			carregaMapa();

		for (var i = 0; i < dados.length; i++) {
			try {
				var kmI = parseFloat(dados[i].km_inicial);
				var kmF = parseFloat(dados[i].km_final);

				if (kmI == kmF) {
					kmI -= 0.2;
					kmF += 0.2;

					dados[i].km_inicial = kmI;
					dados[i].km_final = kmF;
				}

				var br = dados[i].br.split('-')[1];
				dados[i].br = br;
			} catch(e) {}
		}

		dados = jQuery.unique(dados);

		for (var i = 0; i < dados.length; i++)
			map.on("load", executarMapa(map, dados[i]));
	});
}

function changeRenderer(featureLayer) {
	var symbol = null;

	switch (featureLayer.geometryType) {
		case 'esriGeometryPolyline':
			symbol = new esri.symbol.SimpleLineSymbol(esri.symbol.SimpleLineSymbol.STYLE_SOLID, new dojo.Color(hexToRgb("#e8f00b")), 5);
			break;
	}

	if (symbol)
		featureLayer.setRenderer(new esri.renderer.SimpleRenderer(symbol));

	return featureLayer;
}
function hexToRgb(hex) {
	var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
	return result && hex !== "#ffffff" ? [parseInt(result[1], 16), parseInt(result[2], 16), parseInt(result[3], 16) ] : [ 255, 0, 0 ];
}

function criarLayer(map, graphic, nm_versao) {
	var featureCollection = {
			layerDefinition : {
				"geometryType" : "esriGeometryPolyline",
				"objectIdField" : "ObjectID",
				"drawingInfo" : {
					"renderer" : {
						"Simple Renderer" : {
							"symbol" : {
								"Style" : "esriSLSSolid",
								"Color" : [ 255, 0, 0, 1 ],
								"width" : 3
							},
							"Label" : null,
							"Description" : null
						},
						"Transparency" : 0
					}
				},
				"fields" : [ {
					"name" : "ObjectID",
					"alias" : "ObjectID",
					"type" : "esriFieldTypeOID"
				}, {
					"name" : "url",
					"alias" : "url",
					"type" : "esriFieldTypeString"
				} ]
			},
			featureSet : {
				features : [ graphic ],
				geometryType : "esriGeometryPolyline"
			}
	};

	map.addLayer(changeRenderer(new esri.layers.FeatureLayer(
			featureCollection, {
				infoTemplate : new esri.InfoTemplate("Details", "${*}"),
				className : nm_versao
			})));
}

function executarMapa(map, dados){ 
	if (dados){
		setLoaderState(true);
		$.ajax({
			type : "Post",
			url : "http://servicos.dnit.gov.br/segmentador/api/values",
			data : JSON.stringify(dados),
			dataType : "json",
			contentType : "application/json",
			success : function(response) {
				setLoaderState(true);
				try {
					var result = response.Retorno
					var singlePathPolyline = new esri.geometry.Polyline({
						"paths" : result.coordinates,
						"spatialReference" : {
							"wkid" : 4326
						}
					});
					try {
						var i = singlePathPolyline.paths.length - 1;
						var p;
						
						try {
							p = parseInt((singlePathPolyline.paths[i].length - 1) / 2);
						} catch (e) {
							p = 0;
						}

						var point = singlePathPolyline.getPoint(i, p);
						var graphic = new esri.Graphic(singlePathPolyline, null, {
							"DNIT" : "<a href=\"http://servicos.dnit.gov.br/vgeo?lat=" + point.y + "&lon=" + point.x + "\"  target='_blank'>mais detalhes</a>"
						});
					} catch (e) {
						console.log(e)
					}
					criarLayer(map, graphic, result.nm_versao);

					map.setExtent(singlePathPolyline.getExtent());
				} catch (e) {
					console.log(e);
				}
				setLoaderState(false);

			},
			error : function(xhr, status, error) {
				console.log(error);
			}
		});
	}
}

function setLoaderState(bActivate) {
	if (bActivate)
		$("#map-loader-container").fadeIn(100);
	else
		$("#map-loader-container").fadeOut(100);
}