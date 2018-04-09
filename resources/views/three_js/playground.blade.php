@extends('layouts.app')
@section('head')
  <title>Three js playground</title>
  <!-- Style of canvas -->

  <!-- remember to make it to main css -->
  <style type="text/css">
    body{
      margin:0;
      overflow: hidden;
    }
  </style>
  <style type="text/css" src="{{ asset('css/dat.gui.css') }}"></style>
@endsection
@section('content')
  <!-- Canvas  -->

  <canvas id="myCanvas"></canvas>


  <!-- three Script -->
  <script type="text/javascript" src="{{ asset('js/three.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/OrbitControls.js')}}"></script>
  <script type="text/javascript" src="{{ asset('js/dat.gui.js')}}"></script>
  <!-- main TODO remember to make it to main js -->
  <script type="text/javascript">
  	//gui
  	var gui = new dat.GUI();
  	var data = {
  		intensity: 0.5,
  		color: "#010101",
  		COLOR_SLIDER: "#010101"
  	};
  	var options = [
  		"#010101",
  		"#503a22"
  	];

  	var folder = gui.addFolder( 'Light' );
  	//folder.add( data, 'intensity', 0, 1).onChange( changeLight );


  	// var color_options = folder.addColor(data, 'color');
  	// color_options.options(options).onChange(changeLight);




  	// change intensity of light
  	function changeLight(){
  		p_light.intensity = data.intensity;
  		var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(data.color);
  		p_light.color.r = parseInt(result[1],16);
  		p_light.color.g = parseInt(result[2],16);
  		p_light.color.b = parseInt(result[3],16);
  	}


  	// development
  	folder.add( data, 'intensity', 0, 1).onChange( sliderLight );
  	folder.addColor(data,'COLOR_SLIDER').onChange( sliderLight );
  	function sliderLight(){
  		p_light.intensity = data.intensity;
  		var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(data.COLOR_SLIDER);
  		p_light.color.r = parseInt(result[1],16);
  		p_light.color.g = parseInt(result[2],16);
  		p_light.color.b = parseInt(result[3],16);
  	}



  	// camera control
  	var camera_rotate = {
  		rotate:false,
  	};
  	var camera_folder = gui.addFolder('Camera');
  	camera_folder.add(camera_rotate,"rotate").onChange(toggleRotate);
  	function toggleRotate(){
  		controls.autoRotate = camera_rotate.rotate;
  	}

  	// chnage camera

  	folder.open();
  	camera_folder.open();



  	// remderer
  	var renderer = new THREE.WebGLRenderer({canvas: document.getElementById('myCanvas'), antialias: true});
  	renderer.setClearColor(0x000000);
  	renderer.setPixelRatio(window.devicePixelRatio);
  	renderer.setSize(window.innerWidth, window.innerHeight);
  	document.body.appendChild( renderer.domElement );

  	var camera = new THREE.PerspectiveCamera(60, window.innerWidth/ window.innerHeight,1,2000);
  	camera.position.set( 400, 250, 0 );

  	var scene = new THREE.Scene();
  	// all stuff to be added to the scene


  	// object
  	var geometry = new THREE.BoxGeometry(50, 50, 50);
  	var material = new THREE.MeshLambertMaterial({
  		color:0xd3d3d3,
  	});
  	var mesh = new THREE.Mesh(geometry,material);
  	mesh.position.set(0,60,0);
  	scene.add(mesh);

  	// floor
  	var floor = new THREE.PlaneGeometry(1000,1000,100,100);
  	var floor_material = new THREE.MeshLambertMaterial({color:0xa9a9a9});
  	var floor_mesh = new THREE.Mesh(floor,floor_material);
  	floor_mesh.rotation.x = -1.5708;
  	floor_mesh.position.y = 0;
  	scene.add(floor_mesh);


  	//light
  	var light = new THREE.AmbientLight(0xffffff, 0.5); // color , intensity
  	scene.add(light);

  	var p_light = new THREE.PointLight(0xffffff,0.5,500,2);
  	p_light.position.y = 200;
  	scene.add(p_light);

  	var program = function ( context ) {
  		context.beginPath();
  		context.arc( 0, 0, 0.5, 0, PI2, true );
  		context.fill();
  	};




  	// shadows
  	renderer.shadowMap.enabled = true;
  	renderer.shadowMap.type = THREE.PCFShadowMap;
  	p_light.target = mesh;
  	p_light.castShadow = true;
  	p_light.shadow.bias = 0.001;
  	p_light.shadow.mapSize.width =512;
  	p_light.shadow.mapSize.height = 512;
  	mesh.castShadow = true;
  	floor_mesh.receiveShadow = true;


  	// controls
  	var controls = new THREE.OrbitControls( camera, renderer.domElement );
  	//controls.addEventListener( 'change', render ); // call this only in static scenes (i.e., if there is no animation loop)
  	controls.enableDamping = true; // an animation loop is required when either damping or auto-rotation are enabled
  	controls.dampingFactor = 0.25;
  	controls.panningMode = THREE.HorizontalPanning; // default is THREE.ScreenSpacePanning
  	controls.minDistance = 100;
  	controls.maxDistance = 500
  	controls.maxPolarAngle = Math.PI / 2;
  	controls.enablePan = false;
  	//controls.enabled = false;
  	controls.autoRotateSpeed = 0.5;
  	controls.autoRotate = false;
  	controls.target = mesh.position;
  	controls.update();




  	requestAnimationFrame(render);
  	function render() {
  		// body...
  		requestAnimationFrame(render);
  		mesh.rotation.x += 0.05;
  		mesh.rotation.y += 0.05;


  		renderer.render(scene, camera);
  		controls.update();

  	}

  	// function animate() {

  	//   requestAnimationFrame( animate );

  	//   // required if controls.enableDamping or controls.autoRotate are set to true
  	//   controls.update();

  	//   renderer.render( scene, camera );

  	// }

  	window.addEventListener( 'resize', function () {

  		camera.aspect = window.innerWidth / window.innerHeight;
  		camera.updateProjectionMatrix();

  		renderer.setSize( window.innerWidth, window.innerHeight );

  	}, false );
  	render();

  </script>
@endsection
