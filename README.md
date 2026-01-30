# Technical Assignment: Multi-tenant Event Ingestion System

This documentation outlines the design and implementation strategy for the multi-tenant event ingestion system.

---

## 🚀 1. Tenant Identification Strategy

> **Question:** How would tenant identification work (e.g., request data, headers, domains)?

### Implementation
Tenant identification is handled primarily via **request data**. The API receives a `tenant_key` within the request payload or query parameters.

- **Resolution:** The system resolves the tenant by querying the `tenants` table using the unique `tenant_key`.
- **Scoping:** Once resolved, all subsequent operations (devices, events, queries) are strictly scoped to that `tenant_id`.

> [!NOTE]
> For production-grade systems, identification can be extended to Headers (`X-Tenant-Key`), or JWT claims for authenticated sessions.

---

## 🛡️ 2. Data Isolation & Integrity

> **Question:** How do you ensure isolation at the database and application levels?

### Database Level
Isolation is enforced through strict constraints and foreign key relationships:
- **Composite Unique Keys:**
  - `UNIQUE (tenant_id, device_uid)`
  - `UNIQUE (tenant_id, event_uid)`
- **Schema Design:** All core tables include a mandatory `tenant_id` foreign key.

### Application Level
- **Mandatory Scoping:** Every request must resolve the tenant context first.
- **Query Enforcement:** No query is permitted to execute without an explicit `tenant_id` filter.

> [!TIP]
> Use Laravel **Global Scopes** to automatically apply the `tenant_id` filter to all Eloquent queries.

---

## 🧪 3. Reliability & Error Handling

> **Question:** How do you handle system reliability and prevent data duplication?

- **Idempotency:** Utilize database constraints to prevent duplicate event ingestion, ensuring that retried requests do not result in duplicate records.
- **Consistent Responses:** Implement a centralized error-handling strategy in a base controller for standardized API responses.
- **Data Integrity:** Rely on database-level guarantees (foreign keys/transactions) rather than just application logic.

---

## 📈 4. Scalability & Performance

> **Question:** What measures ensure the system remains performant under high load?

| Feature | Strategy |
| :--- | :--- |
| **Architecture** | Transition to Modular/HMVC structure as the codebase grows. |
| **Processing** | Use Asynchronous Queues and background jobs for high-frequency events. |
| **Optimization** | Implement Caching for frequently accessed tenant metadata. |
| **Code Quality** | Adhere to SOLID principles to maintain long-term scalability. |

---

## ⚠️ 5. Challenges & Edge Cases

> **Question:** What are the common failure points in multi-tenant environments?

- **Tenant-Aware Caching:** Cache keys must include the tenant ID (e.g., `tenant:{id}:events`) to prevent data leakage.
- **Background Jobs:** Jobs must explicitly carry the `tenant_id` in their payload to maintain context during execution.
- **"Noisy Neighbor" Effect:** High-volume tenants can impact system performance; rate limiting per tenant is recommended.
- **Observability:** Logs must include `tenant_id` for effective troubleshooting and traceability.

---

## 🔒 6. Security & Access Control

> **Question:** How is unauthorized access prevented?

- **Authentication:** Managed via [Laravel Sanctum](https://laravel.com/docs/sanctum).
- **Authorization:** Implementation of a Roles & Permissions package to define granular access levels.
- **Validation:** Strict validation of all incoming data to prevent injection or cross-tenant data access.

---

## 📝 Project Meta

- **Time Spent:** Approximately 2 days.
- **Completion Status:** I believe the solution is "good enough" for the requirements of this assignment, striking a balance between robust architecture and implementation speed.