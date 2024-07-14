package com.example.foodtruckmapfinder;

import android.Manifest;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.os.Bundle;
import android.text.TextUtils;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;
import androidx.annotation.NonNull;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.core.app.ActivityCompat;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.Volley;
import com.google.android.gms.location.FusedLocationProviderClient;
import com.google.android.gms.location.LocationServices;
import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.OnMapReadyCallback;
import com.google.android.gms.maps.SupportMapFragment;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.MarkerOptions;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public class MainActivity extends AppCompatActivity implements OnMapReadyCallback {
    private GoogleMap mMap;
    private EditText searchBar;
    private FusedLocationProviderClient fusedLocationClient;
    private static final String URL = "http://192.168.0.107/get_food_trucks.php"; // Use your server's IP address
    private static final int LOCATION_PERMISSION_REQUEST_CODE = 1000;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        Toolbar toolbar = findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        getSupportActionBar().setTitle("Food Truck Finder");

        searchBar = findViewById(R.id.search_bar);
        Button zoomInButton = findViewById(R.id.zoom_in);
        Button zoomOutButton = findViewById(R.id.zoom_out);
        Button locateButton = findViewById(R.id.locate_button);

        fusedLocationClient = LocationServices.getFusedLocationProviderClient(this);

        SupportMapFragment mapFragment = (SupportMapFragment) getSupportFragmentManager()
                .findFragmentById(R.id.map);
        mapFragment.getMapAsync(this);

        zoomInButton.setOnClickListener(v -> mMap.animateCamera(CameraUpdateFactory.zoomIn()));
        zoomOutButton.setOnClickListener(v -> mMap.animateCamera(CameraUpdateFactory.zoomOut()));
        locateButton.setOnClickListener(v -> locateCurrentLocation());

        searchBar.setOnEditorActionListener((v, actionId, event) -> {
            String query = searchBar.getText().toString().trim();
            if (!TextUtils.isEmpty(query)) {
                searchFoodTrucks(query);
            }
            return false;
        });
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.main_menu, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        if (item.getItemId() == R.id.action_about_us) {
            Intent intent = new Intent(MainActivity.this, AboutUsActivity.class);
            startActivity(intent);
            return true;
        }
        return super.onOptionsItemSelected(item);
    }

    @Override
    public void onMapReady(GoogleMap googleMap) {
        mMap = googleMap;
        fetchFoodTrucks();
        enableMyLocation();
    }

    private void enableMyLocation() {
        if (ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED &&
                ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
            ActivityCompat.requestPermissions(this, new String[]{Manifest.permission.ACCESS_FINE_LOCATION}, LOCATION_PERMISSION_REQUEST_CODE);
        } else {
            mMap.setMyLocationEnabled(true);
        }
    }

    private void locateCurrentLocation() {
        if (ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION) == PackageManager.PERMISSION_GRANTED ||
                ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_COARSE_LOCATION) == PackageManager.PERMISSION_GRANTED) {
            fusedLocationClient.getLastLocation()
                    .addOnSuccessListener(this, location -> {
                        if (location != null) {
                            LatLng currentLocation = new LatLng(location.getLatitude(), location.getLongitude());
                            mMap.moveCamera(CameraUpdateFactory.newLatLngZoom(currentLocation, 15));
                            mMap.addMarker(new MarkerOptions().position(currentLocation).title("Current Location"));
                        } else {
                            Log.e("LocationError", "Location is null");
                            showToast("Unable to find current location.");
                        }
                    })
                    .addOnFailureListener(e -> {
                        Log.e("LocationError", "Failed to get location: " + e.getMessage());
                        showToast("Failed to get location.");
                    });
        } else {
            ActivityCompat.requestPermissions(this, new String[]{Manifest.permission.ACCESS_FINE_LOCATION}, LOCATION_PERMISSION_REQUEST_CODE);
        }
    }

    private void fetchFoodTrucks() {
        RequestQueue queue = Volley.newRequestQueue(this);
        JsonArrayRequest jsonArrayRequest = new JsonArrayRequest(Request.Method.GET, URL, null,
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response) {
                        try {
                            for (int i = 0; i < response.length(); i++) {
                                JSONObject foodTruck = response.getJSONObject(i);
                                String name = foodTruck.getString("name");
                                String truckId = foodTruck.getString("truck_id"); // Assuming truck_id exists
                                double lat = foodTruck.getDouble("latitude");
                                double lng = foodTruck.getDouble("longitude");

                                LatLng location = new LatLng(lat, lng);
                                mMap.addMarker(new MarkerOptions().position(location).title(name)).setTag(truckId);

                                if (i == 0) {
                                    mMap.moveCamera(CameraUpdateFactory.newLatLngZoom(location, 10));
                                }
                            }

                            // Set a marker click listener
                            mMap.setOnMarkerClickListener(marker -> {
                                String truckId = (String) marker.getTag();
                                showFoodTruckInfo(marker.getTitle(), truckId);
                                return true; // Prevent default behavior
                            });

                        } catch (JSONException e) {
                            e.printStackTrace();
                            showToast("Error parsing food truck data.");
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        error.printStackTrace();
                        showToast("Error fetching food trucks.");
                    }
                });

        queue.add(jsonArrayRequest);
    }

    private void showFoodTruckInfo(String name, String truckId) {
        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setTitle(name);
        builder.setMessage("Click below to view the menu.");
        builder.setPositiveButton("View Menu", (dialog, which) -> {
            Intent intent = new Intent(MainActivity.this, MenuActivity.class);
            intent.putExtra("truck_id", truckId); // Pass the truck_id to the MenuActivity
            startActivity(intent);
        });
        builder.setNegativeButton("Cancel", null);
        builder.show();
    }

    private void searchFoodTrucks(String query) {
        RequestQueue queue = Volley.newRequestQueue(this);
        String searchUrl = URL + "?search=" + query; // Assuming your PHP script can handle search queries
        JsonArrayRequest jsonArrayRequest = new JsonArrayRequest(Request.Method.GET, searchUrl, null,
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response) {
                        mMap.clear(); // Clear existing markers
                        try {
                            for (int i = 0; i < response.length(); i++) {
                                JSONObject foodTruck = response.getJSONObject(i);
                                String name = foodTruck.getString("name");
                                double lat = foodTruck.getDouble("latitude");
                                double lng = foodTruck.getDouble("longitude");

                                LatLng location = new LatLng(lat, lng);
                                mMap.addMarker(new MarkerOptions().position(location).title(name));
                                if (i == 0) {
                                    mMap.moveCamera(CameraUpdateFactory.newLatLngZoom(location, 10));
                                }
                            }
                        } catch (JSONException e) {
                            e.printStackTrace();
                            showToast("Error parsing search results.");
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        error.printStackTrace();
                        showToast("Error fetching search results.");
                    }
                });

        queue.add(jsonArrayRequest);
    }

    private void showToast(String message) {
        Toast.makeText(this, message, Toast.LENGTH_SHORT).show();
    }

    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults);
        if (requestCode == LOCATION_PERMISSION_REQUEST_CODE) {
            if (grantResults.length > 0 && grantResults[0] == PackageManager.PERMISSION_GRANTED) {
                enableMyLocation();
            }
        }
    }
}
