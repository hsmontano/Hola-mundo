EditText fecha_inicio, fecha_fin;
    Button reporte;

    private ProgressDialog pDialog;

    GenerarReporte reporte_g;

    JSONParser jsonParser = new JSONParser();

    private static final String URL = "http://192.168.1.42/prueba/consultar_reporte.php";

    private static final String TAG_SUCCESS = "success";
    private static final String TAG_MESSAGE = "message";
    private static final String TAG_REPORTE = "reporte";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_reporte);

       fecha_inicio = (EditText)findViewById(R.id.txt_fechaInicio);
       fecha_fin = (EditText)findViewById(R.id.txt_fechaFin);

       reporte = (Button)findViewById(R.id.btn_generar);

       reporte.setOnClickListener(this);
    }

    @Override
    public void onClick(View v) {
        String fecha_i = fecha_inicio.getText().toString();
        String fecha_f = fecha_fin.getText().toString();

        reporte_g = new GenerarReporte();
        reporte_g.execute(fecha_i, fecha_f);
    }
    class GenerarReporte extends AsyncTask<String, String, String>{
        @Override
        protected void onPreExecute() {
            super.onPreExecute();
        }

        @Override
        protected String doInBackground(String... args) {

            int success;

            String resultado1 = "";
            String resultado2 = "";
            String resultado3 = "";
            String resultado4 = "";
            String resultado5 = "";
            String resultado6 = "";
            String resultado7 = "";
            String resultado8 = "";
            String resultado9 = "";


            String fecha_i = args[0],
                    fecha_f = args[1];

            List<NameValuePair>params = new ArrayList<NameValuePair>();

            try{
                params.add(new BasicNameValuePair("fecha_inicio",fecha_i));
                params.add(new BasicNameValuePair("fecha_fin",fecha_f));

                Log.d("request!", "starting");
                // getting product details by making HTTP request
                JSONObject json = jsonParser.makeHttpRequest(URL, "POST", params);

                // check your log for json response
                Log.d("Login attempt", json.toString());

                success = json.getInt(TAG_SUCCESS);

                if (success == 1){

                    JSONArray jsonArray = json.getJSONArray(TAG_REPORTE);

                    for (int i = 0; i < jsonArray.length(); i++){

                        JSONObject Json = jsonArray.getJSONObject(i);

                        resultado1 = Json.getString("idFactura");
                        resultado2  = Json.getString("total");
                        resultado3 = Json.getString("pago");
                        resultado4 = Json.getString("descuento");
                        resultado5 = Json.getString("empleado");
                        resultado6 = Json.getString("cedula");
                        resultado7 = Json.getString("persona");
                        resultado8 = Json.getString("apellido");
                        resultado9 = Json.getString("identificacion");
                        resultado9 = Json.getString("operacion");

                    }
                }else{
                        resultado1 = "no se encontraron registros";
                }

            }catch (JSONException e){
                e.getStackTrace();
            }
            return  " | " + resultado1 + " | " + resultado2 + " | " + resultado3 + " \n " + resultado4 + " | " + resultado5 + " | " + resultado6 + " \n " + resultado7 + " | " + resultado8 + " | " + resultado9 + " | ";
        }

        @Override
        protected void onPostExecute(String s) {
            reporte.setText(s);
        }
    }