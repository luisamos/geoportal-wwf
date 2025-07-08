ALTER TABLE public.usuario
ADD COLUMN intentos_login INTEGER DEFAULT 0;

CREATE OR REPLACE FUNCTION verificar_intentos_login()
RETURNS TRIGGER AS $$
BEGIN
  -- Si los intentos llegan a 3, bloquear usuario
  IF NEW.intentos_login >= 3 THEN
    NEW.usuari_estado := 0;
  END IF;

  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_verificar_intentos_login
BEFORE UPDATE ON public.usuario
FOR EACH ROW
WHEN (OLD.intentos_login IS DISTINCT FROM NEW.intentos_login)
EXECUTE FUNCTION verificar_intentos_login();


UPDATE public.usuario 
SET usuari_estado = CASE 
	WHEN usuari_estado = 1 THEN 0 
	ELSE 1 
END 
WHERE id_usuario = 11 RETURNING usuari_estado;