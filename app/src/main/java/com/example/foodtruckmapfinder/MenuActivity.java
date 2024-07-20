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
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.google.gson.Gson;
import com.google.gson.GsonBuilder;

import java.util.ArrayList;
import java.util.List;

public class MenuActivity extends AppCompatActivity {
    private RecyclerView menuRecyclerView;
    private static final String MENU_URL = "http://192.168.0.107/food_truck_mapper/get_menu.php"; // Adjust your URL
    private RequestQueue requestQueue;
    private Gson gson;
    private List<MenuItem> menuItems;
    private MenuAdapter menuAdapter;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_menu);

        gson = new GsonBuilder().create();
        menuItems = new ArrayList<>();

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

        // Retrieve truck_id from intent extras
        String truckId = getIntent().getStringExtra("truck_id");
        if (truckId != null) {
            // Fetch menu data based on truck_id
            fetchMenuData(truckId);
        }
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.main_menu, menu);
        return true;
    }

    private void fetchMenuData(String truckId) {
        String menuUrl = MENU_URL + "?truck_id=" + truckId;
        requestQueue = Volley.newRequestQueue(getApplication());
        StringRequest stringRequest = new StringRequest(Request.Method.GET, menuUrl, onSuccess, onError);
        requestQueue.add(stringRequest);
    }

    private Response.Listener<String> onSuccess = response -> {
        MenuItem[] menuArray = gson.fromJson(response, MenuItem[].class);
        menuItems.clear();
        for (MenuItem item : menuArray) {
            menuItems.add(item);
        }
        menuAdapter = new MenuAdapter(MenuActivity.this, menuItems, requestQueue);
        menuRecyclerView.setAdapter(menuAdapter);
    };

    private Response.ErrorListener onError = error -> {
        Toast.makeText(getApplicationContext(), error.getMessage(), Toast.LENGTH_LONG).show();
    };
}
