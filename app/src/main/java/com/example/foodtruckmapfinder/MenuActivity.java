package com.example.foodtruckmapfinder;

import android.content.Intent;
import android.os.Bundle;
import android.view.Menu;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

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
import java.util.List;

public class MenuActivity extends AppCompatActivity {
    private RecyclerView menuRecyclerView;
    private static final String MENU_URL = "http://localhost/food_truck_mapper/get_menu.php"; // Adjust your URL
    private String truckId;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_menu);

        Toolbar toolbar = findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        getSupportActionBar().setTitle("Menu");

        // Inflate the menu
        toolbar.inflateMenu(R.menu.main_menu);
        toolbar.setOnMenuItemClickListener(item -> {
            int itemId = item.getItemId();
            if (itemId == R.id.action_home) {  // Handle Home action
                startActivity(new Intent(MenuActivity.this, MainActivity.class));
                return true;
            } else if (itemId == R.id.action_map) {  // Handle Map action
                startActivity(new Intent(MenuActivity.this, MapActivity.class));
                return true;
            } else if (itemId == R.id.action_about_us) {  // Handle About Us action
                startActivity(new Intent(MenuActivity.this, AboutUsActivity.class));
                return true;
            }
            return false;
        });

        menuRecyclerView = findViewById(R.id.menu_recycler_view);
        menuRecyclerView.setLayoutManager(new LinearLayoutManager(this));
        truckId = getIntent().getStringExtra("truck_id");

        fetchMenu();
    }

    private void fetchMenu() {
        RequestQueue queue = Volley.newRequestQueue(this);
        String url = MENU_URL + "?truck_id=" + truckId; // Assuming your PHP can handle this query

        JsonArrayRequest jsonArrayRequest = new JsonArrayRequest(Request.Method.GET, url, null,
                response -> {
                    List<MenuItem> menuItems = new ArrayList<>();
                    try {
                        for (int i = 0; i < response.length(); i++) {
                            JSONObject menuItemObj = response.getJSONObject(i);
                            String name = menuItemObj.getString("menu_name");
                            String desc = menuItemObj.getString("menu_desc");
                            double price = menuItemObj.getDouble("menu_price");
                            String imageUrl = menuItemObj.getString("menu_image");

                            menuItems.add(new MenuItem(name, desc, price, imageUrl));
                        }
                        MenuAdapter adapter = new MenuAdapter(MenuActivity.this, menuItems, queue);
                        menuRecyclerView.setAdapter(adapter);
                    } catch (JSONException e) {
                        e.printStackTrace();
                        showToast("Error parsing menu data.");
                    }
                },
                error -> {
                    error.printStackTrace();
                    showToast("Error fetching menu.");
                });

        queue.add(jsonArrayRequest);
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.main_menu, menu);
        return true;
    }

    private void showToast(String message) {
        Toast.makeText(this, message, Toast.LENGTH_SHORT).show();
    }
}
