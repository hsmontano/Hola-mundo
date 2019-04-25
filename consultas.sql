/*consulta de una factura a la base de datos*/

SELECT f.total, f.pago, f.descuento, f.fecha_creacion, p.nombre AS persona, c.fecha_creacion, u.nombre AS usuario, t.nombre AS operacion 
FROM factura f INNER JOIN persona p ON f.persona_id = p.idPersona 
			   INNER JOIN usuario u ON f.usuario_id = u.idUsuario 
			   INNER JOIN tipo_operacion t ON f.tipo_operacion_id = t.idTipo_operacion 
			   INNER JOIN caja c ON f.caja_id = c.idCaja;