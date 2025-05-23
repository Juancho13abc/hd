document.addEventListener('DOMContentLoaded', () => {
  const productos = document.querySelectorAll('.productos-container > div');
  let carrito = JSON.parse(localStorage.getItem('carrito')) || []; // Restaurar el carrito desde localStorage

  const sincronizarCarrito = () => {
    const carritoLista = document.querySelector('.carrito-lista');
    const carritoTotal = document.querySelector('.carrito-total');
    carritoLista.innerHTML = '';
    let total = 0;

    carrito.forEach((producto, index) => {
      const item = document.createElement('li');
      item.classList.add('carrito-item');
      item.innerHTML = `
        <img src="${producto.imagen}" alt="${producto.nombre}" class="carrito-item-imagen">
        <div class="carrito-item-detalles">
          <p class="carrito-item-nombre">${producto.nombre}</p>
          <p class="carrito-item-precio">${producto.cantidad} x ${producto.precioTexto}</p>
        </div>
        <div class="carrito-item-controles">
          <button class="boton-eliminar" data-index="${index}">-</button>
          <button class="boton-aumentar" data-index="${index}">+</button>
          <button class="boton-basura" data-index="${index}">🗑️</button>
        </div>
      `;
      carritoLista.appendChild(item);
      total += producto.cantidad * producto.precioNumerico;

      // Funcionalidad de los botones en el carrito
      item.querySelector('.boton-eliminar').addEventListener('click', () => modificarCantidad(producto.nombre, -1));
      item.querySelector('.boton-aumentar').addEventListener('click', () => modificarCantidad(producto.nombre, 1));
      item.querySelector('.boton-basura').addEventListener('click', () => eliminarProducto(producto.nombre));
    });

    // Mostrar "0" si no hay productos en el carrito
    carritoTotal.textContent = carrito.length > 0 
      ? `$${total.toFixed(3).replace(/\B(?=(\d{3})+(?!\d))/g, '.')}` 
      : '0';

    sincronizarProductos();
  };

  const sincronizarProductos = () => {
    productos.forEach((producto) => {
      const nombre = producto.querySelector('h2').textContent;
      const productoEnCarrito = carrito.find(item => item.nombre === nombre);
      const inputCantidad = producto.querySelector('input');
      const contadorContainer = producto.querySelector('.contador-container');
      const botonEliminar = producto.querySelector('.boton-eliminar');
      const botonBasura = producto.querySelector('.boton-basura');
      const botonCarrito = producto.querySelector('.boton-carrito');

      if (productoEnCarrito) {
        inputCantidad.value = productoEnCarrito.cantidad;
        contadorContainer.style.display = 'flex';
        botonEliminar.style.display = productoEnCarrito.cantidad > 1 ? 'inline-block' : 'none';
        botonBasura.style.display = productoEnCarrito.cantidad === 1 ? 'inline-block' : 'none';
        botonCarrito.textContent = 'Añadido al carrito';
      } else {
        inputCantidad.value = 0;
        contadorContainer.style.display = 'none';
        botonCarrito.textContent = 'Añadir al carrito';
      }
    });
  };

  const modificarCantidad = (nombre, cambio) => {
    const productoExistente = carrito.find(item => item.nombre === nombre);

    if (productoExistente) {
      productoExistente.cantidad += cambio;

      if (productoExistente.cantidad <= 0) {
        carrito = carrito.filter(item => item.nombre !== nombre);
      }
    }

    sincronizarCarrito();
    localStorage.setItem('carrito', JSON.stringify(carrito));
  };

  const eliminarProducto = (nombre) => {
    carrito = carrito.filter(item => item.nombre !== nombre);
    sincronizarCarrito();
    localStorage.setItem('carrito', JSON.stringify(carrito));
  };

  productos.forEach((producto) => {
    const botonCarrito = producto.querySelector('.boton-carrito');
    const botonAumentar = producto.querySelector('.boton-aumentar');
    const botonEliminar = producto.querySelector('.boton-eliminar');
    const botonBasura = producto.querySelector('.boton-basura');

    botonCarrito.addEventListener('click', () => {
      const nombre = producto.querySelector('h2').textContent;
      const precioTexto = producto.querySelector('p').textContent;
      const precioNumerico = parseFloat(precioTexto.replace(/[^0-9.]/g, ''));
      const codigo = producto.querySelector('p strong').textContent;
      const imagen = producto.querySelector('img')?.src || 'src/assets/default-image.jpg';

      const productoExistente = carrito.find(item => item.nombre === nombre);

      if (productoExistente) {
        productoExistente.cantidad++;
      } else {
        carrito.push({ nombre, precioTexto, precioNumerico, cantidad: 1, imagen, codigo });
      }

      sincronizarCarrito();
      localStorage.setItem('carrito', JSON.stringify(carrito));
    });

    botonAumentar.addEventListener('click', () => {
      const nombre = producto.querySelector('h2').textContent;
      modificarCantidad(nombre, 1);
    });

    botonEliminar.addEventListener('click', () => {
      const nombre = producto.querySelector('h2').textContent;
      modificarCantidad(nombre, -1);
    });

    botonBasura.addEventListener('click', () => {
      const nombre = producto.querySelector('h2').textContent;
      eliminarProducto(nombre);
    });
  });

  const carritoIcon = document.querySelector('.fa-shopping-cart');
  const carritoLateral = document.querySelector('.carrito-lateral');
  const carritoCerrar = document.querySelector('.carrito-cerrar');

  carritoIcon.addEventListener('click', () => {
    carritoLateral.classList.add('visible');
    sincronizarCarrito();
  });

  carritoCerrar.addEventListener('click', () => {
    carritoLateral.classList.remove('visible');
  });

  const botonPagar = document.querySelector('.carrito-pagar');
  botonPagar.addEventListener('click', () => {
    window.location.href = 'pago.html';
  });

  sincronizarCarrito();
});
