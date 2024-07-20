package com.example.foodtruckmapfinder;

import android.content.Intent;
import android.content.pm.PackageManager;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.View;
import android.widget.SearchView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.OnMapReadyCallback;
import com.google.android.gms.maps.SupportMapFragment;
import com.google.android.gms.maps.model.BitmapDescriptorFactory;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.Marker;
import com.google.android.gms.maps.model.MarkerOptions;
import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
public class MapActivity extends AppCompatActivity implements OnMapReadyCallback {

    private GoogleMap mMap;
    private static final String FOOD_TRUCKS_URL = "http://192.168.0.107/food_truck_mapper/get_food_trucks.php";

    private static final int LOCATION_PERMISSION_REQUEST_CODE = 1000;
    private static final LatLng KANGAR_LAT_LNG = new LatLng(6.4419, 100.2001);

    private RequestQueue requestQueue;
    private Gson gson;

    private SearchView searchView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_map);

        gson = new GsonBuilder().create();

        Toolbar toolbar = findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        getSupportActionBar().setTitle("Food Truck Finder");

        toolbar.inflateMenu(R.menu.main_menu);

        toolbar.setOnMenuItemClickListener(item -> {
            int itemId = item.getItemId();
            if (itemId == R.id.action_home) {
                startActivity(new Intent(MapActivity.this, MainActivity.class));
                return true;
            } else if (itemId == R.id.action_map) {
                showToast("You're already on the map");
                return true;
            } else if (itemId == R.id.action_about_us) {
                startActivity(new Intent(MapActivity.this, AboutUsActivity.class));
                return true;
            }
            return false;
        });

        searchView = findViewById(R.id.action_search);
        searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
            @Override
            public boolean onQueryTextSubmit(String query) {
                // Perform search based on query (if needed)
                return false;
            }

            @Override
            public boolean onQueryTextChange(String newText) {
                // Handle text changes in search view (if needed)
                return false;
            }
        });

        SupportMapFragment mapFragment = (SupportMapFragment) getSupportFragmentManager()
                .findFragmentById(R.id.map);
        mapFragment.getMapAsync(this);

        findViewById(R.id.zoom_in).setOnClickListener(view -> {
            if (mMap != null) {
                mMap.animateCamera(CameraUpdateFactory.zoomIn());
            }
        });

        findViewById(R.id.zoom_out).setOnClickListener(view -> {
            if (mMap != null) {
                mMap.animateCamera(CameraUpdateFactory.zoomOut());
            }
        });
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.main_menu, menu);
        return true;
    }

    @Override
    public void onMapReady(GoogleMap googleMap) {
        mMap = googleMap;

        mMap.moveCamera(CameraUpdateFactory.newLatLngZoom(KANGAR_LAT_LNG, 14)); // Adjust zoom level as needed
        Marker kangarMarker = mMap.addMarker(new MarkerOptions()
                .position(KANGAR_LAT_LNG)
                .title("Kangar")
                .snippet("Capital of Perlis")
                .icon(BitmapDescriptorFactory.defaultMarker(BitmapDescriptorFactory.HUE_AZURE)));
        kangarMarker.setTag("kangar"); // Example tag for Kangar marker

        mMap.setOnMarkerClickListener(marker -> {
            marker.showInfoWindow();
            return true;
        });

        mMap.setOnInfoWindowClickListener(marker -> {
            String truckId = (String) marker.getTag(); // Retrieve truckId from marker tag
            if (truckId != null) {
                Intent intent = new Intent(MapActivity.this, MenuActivity.class);
                intent.putExtra("truck_id", truckId); // Pass truckId to MenuActivity
                startActivity(intent);
            }
        });

        mMap.setInfoWindowAdapter(new GoogleMap.InfoWindowAdapter() {
            @Override
            public View getInfoWindow(Marker marker) {
                return null; // Use default InfoWindow frame
            }

            @Override
            public View getInfoContents(Marker marker) {
                View v = getLayoutInflater().inflate(R.layout.info_window_layout, null);

                TextView title = v.findViewById(R.id.info_title);
                TextView snippet = v.findViewById(R.id.info_snippet);

                // Retrieve additional info from marker snippet
                String[] markerData = marker.getSnippet() != null ? marker.getSnippet().split("\\|") : new String[]{"", "", ""};
                String operatorName = markerData.length > 0 ? markerData[0] : "Unknown";
                String menuNames = markerData.length > 1 ? markerData[1] : "No menu available";
                String businessHours = markerData.length > 2 ? markerData[2] : "No hours available";

                title.setText(marker.getTitle());
                snippet.setText("Operator: " + operatorName + "\nMenu: " + menuNames + "\nHours: " + businessHours);

                return v;
            }
        });

        sendRequest();
        enableMyLocation();
    }

    private void enableMyLocation() {
        if (ContextCompat.checkSelfPermission(this, android.Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED &&
                ContextCompat.checkSelfPermission(this, android.Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
            ActivityCompat.requestPermissions(this, new String[]{android.Manifest.permission.ACCESS_FINE_LOCATION}, LOCATION_PERMISSION_REQUEST_CODE);
        } else {
            mMap.setMyLocationEnabled(true);
        }
    }

    private void showToast(String message) {
        Toast.makeText(this, message, Toast.LENGTH_SHORT).show();
    }

    public void sendRequest() {
        requestQueue = Volley.newRequestQueue(getApplication());
        StringRequest stringRequest = new StringRequest(Request.Method.GET, FOOD_TRUCKS_URL, onSuccess, onError);
        requestQueue.add(stringRequest);
    }

    public Response.Listener<String> onSuccess = response -> {
        if (response == null || response.isEmpty()) {
            Log.d("FoodTruck", "Response is null or empty");
            return;
        }

        FoodTruck[] foodTrucks = gson.fromJson(response, FoodTruck[].class);

        if (foodTrucks == null) {
            Log.d("FoodTruck", "Parsed foodTrucks is null");
            return;
        }

        Log.d("FoodTruck", "Number of Food Truck Data Points: " + foodTrucks.length);

        for (FoodTruck truck : foodTrucks) {
            if (truck == null) {
                Log.d("FoodTruck", "Encountered null FoodTruck object");
                continue;
            }

            try {
                Double latitude = Double.parseDouble(truck.getLatitude());
                Double longitude = Double.parseDouble(truck.getLongitude());
                String truckId = truck.getTruckId(); // Assuming the truck ID is retrieved from JSON
                String title = truck.getName();
                String operatorName = truck.getOperatorName() != null ? truck.getOperatorName() : "Unknown";
                String menuNames = truck.getMenuNames() != null ? String.join(", ", truck.getMenuNames()) : "No menu available";
                String businessHours = truck.getBusinessHours() != null ? truck.getBusinessHours() : "No hours available";

                String snippet = operatorName + "|" + menuNames + "|" + businessHours;

                MarkerOptions markerOptions = new MarkerOptions()
                        .position(new LatLng(latitude, longitude))
                        .title(title)
                        .snippet(snippet);

                Marker marker = mMap.addMarker(markerOptions);
                marker.setTag(truckId); // Set truckId as tag for this marker

            } catch (NumberFormatException e) {
                Log.e("FoodTruck", "Error parsing latitude or longitude: " + e.getMessage());
            }
        }
    };

    public Response.ErrorListener onError = error -> {
        Log.e("VolleyError", "Error fetching data: " + error.getMessage());
        Toast.makeText(getApplicationContext(), "Error fetching data", Toast.LENGTH_LONG).show();
    };

    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults);
        if (requestCode == LOCATION_PERMISSION_REQUEST_CODE) {
            if (grantResults.length > 0 && grantResults[0] == PackageManager.PERMISSION_GRANTED) {
                enableMyLocation();
            } else {
                Toast.makeText(this, "Location permission denied", Toast.LENGTH_SHORT).show();
            }
        }
    }
}
