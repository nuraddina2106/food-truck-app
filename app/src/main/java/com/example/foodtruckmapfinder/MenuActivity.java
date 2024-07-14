package com.example.foodtruckmapfinder;

import android.os.Bundle;
import android.widget.ListView;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.Volley;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import java.util.ArrayList;

public class MenuActivity extends AppCompatActivity {
    private ListView menuListView;
    private static final String MENU_URL = "http://192.168.0.107/get_menus.php"; // Adjust your URL
    private String truckId;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_menu);

        Toolbar toolbar = findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        getSupportActionBar().setTitle("Menu");

        menuListView = findViewById(R.id.menu_list_view);
        truckId = getIntent().getStringExtra("truck_id");

        fetchMenu();
    }

    private void fetchMenu() {
        RequestQueue queue = Volley.newRequestQueue(this);
        String url = MENU_URL + "?truck_id=" + truckId; // Assuming your PHP can handle this query

        JsonArrayRequest jsonArrayRequest = new JsonArrayRequest(Request.Method.GET, url, null,
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response) {
                        ArrayList<MenuItem> menuItems = new ArrayList<>();
                        try {
                            for (int i = 0; i < response.length(); i++) {
                                JSONObject menuItem = response.getJSONObject(i);
                                String name = menuItem.getString("menu_name");
                                String desc = menuItem.getString("menu_desc");
                                double price = menuItem.getDouble("menu_price");

                                menuItems.add(new MenuItem(name, desc, price));
                            }
                            // Set the adapter for your ListView (create a MenuAdapter class)
                            MenuAdapter adapter = new MenuAdapter(MenuActivity.this, menuItems);
                            menuListView.setAdapter(adapter);

                        } catch (JSONException e) {
                            e.printStackTrace();
                            showToast("Error parsing menu data.");
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        error.printStackTrace();
                        showToast("Error fetching menu.");
                    }
                });

        queue.add(jsonArrayRequest);
    }

    private void showToast(String message) {
        Toast.makeText(this, message, Toast.LENGTH_SHORT).show();
    }
}
