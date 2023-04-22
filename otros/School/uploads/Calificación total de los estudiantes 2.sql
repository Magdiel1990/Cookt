select
	s.firstname,
    s.course, 
    s.section,
    ifnull(e.exams,0),
    ifnull(e.fecha, '00-00-0000'),
    ifnull(e.description,'Ninguna'),
    ifnull(sum(p.calificacion),0),    
    r.register,
    ifnull(r.fecha,'00-00-0000')
from
	students as s left join exams as e on e.firstname=s.firstname
    left join participation as p on p.firstname=e.firstname 
    left join register as r on r.firstname=e.firstname 
group by 
	s.firstname,s.course, s.section,  r.register
order by
	s.course, s.firstname

    
/*Nombre
curso
seccion
Examenes
fecha del examen
descripcion
participacion acumulada
registro
fecha de registro
*/